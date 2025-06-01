<?php

namespace Nistech\ContaoQualliIdLogin\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Nistech\ContaoQualliIdLogin\Provider\Exception\QualliIdIdentityProviderException;
use Psr\Http\Message\ResponseInterface;
use Contao\System;

class QualliId extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Domain
     *
     * @var string
     */
    public $domain = 'https://devaccount.nistech.net';

    /**
     * Api domain
     *
     * @var string
     */
    public $apiDomain = 'https://devaccount.nistech.net';

    /**
     * Get authorization url to begin OAuth flow
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->domain . '/connect/authorize';
    }

    /**
     * Get access token url to retrieve token
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->domain . '/connect/token';
    }

    /**
     * Get provider url to fetch user details
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return $this->domain . '/connect/userinfo';
    }

    protected function fetchResourceOwnerDetails(AccessToken $token)
    {
        //$logger = System::getContainer()->get('monolog.logger.contao.general');
                    
        $response = parent::fetchResourceOwnerDetails($token);

        if (empty($response['UserName'])) {
            $url = $this->getResourceOwnerDetailsUrl($token);

            $request = $this->getAuthenticatedRequest(self::METHOD_GET, $url, $token);

            $responseEmail = $this->getParsedResponse($request);

            $response['UserName'] = isset($responseEmail[0]['UserName']) ? $responseEmail[0]['UserName'] : null;
        }

        return $response;
    }

    /**
     * Get the default scopes used by this provider.
     *
     * This should not be a complete list of all scopes, but the minimum
     * required for the provider user interface!
     *
     * @return array
     */
    protected function getDefaultScopes()
    {
        return [
            'offline_access',
            'openid',
            'Qualli.UserType.User',
            'Qualli.UserType.Employee',
            'Qualli.UserType.Member'
        ];
    }

    protected function getScopeSeparator()
    {
        return ' ';
    }

    /**
     * Check a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array             $data     Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $logger = System::getContainer()->get('monolog.logger.contao.error');

        if ($response->getStatusCode() >= 400) {
            $logger->error('Response status code is ' . $response->getStatusCode());
            throw QualliIdIdentityProviderException::clientException($response, $data);
        } elseif (isset($data['error'])) {
            $logger->error('Response error ' . $data['error']);
            throw QualliIdIdentityProviderException::oauthException($response, $data);
        }
    }

    /**
     * Generate a user object from a successful user details request.
     *
     * @param  array       $response
     * @param  AccessToken $token
     * @return \League\OAuth2\Client\Provider\ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        $user = new QualliIdResourceOwner($response);

        return $user->setDomain($this->domain);
    }
}