<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin\OAuth2\Client;

use Contao\CoreBundle\Framework\ContaoFramework;
use Doctrine\DBAL\Connection;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\QualliId;
use Markocupic\ContaoOAuth2Client\Event\CreateOAuth2ProviderEvent;
use Markocupic\ContaoOAuth2Client\OAuth2\Client\AbstractClientFactory;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class QualliIdBackendClientFactory extends AbstractClientFactory
{
    public const NAME = 'qualliid_backend';
    public const PROVIDER = 'qualliid';
    public const CONTAO_FIREWALL = 'contao_backend';

    public function __construct(
        ContaoFramework $framework,
        Connection $connection,
        protected readonly EventDispatcherInterface $eventDispatcher,
        protected readonly RouterInterface $router,
        protected array $config,
    ) {
        parent::__construct($framework, $connection);
    }

    public function createClient(Request $request, array $options = []): AbstractProvider
    {
        $options = array_merge($this->getConfig(), $options);

        // Add required options to the provider constructor
        $opt = [];
        $opt['clientSecret'] = $options['client_secret'];
        $opt['clientId'] = $options['client_id'];
        $opt['redirectUri'] = $this->router->generate($this->getRedirectRoute(), ['_oauth2_client' => $this->getName()], UrlGeneratorInterface::ABSOLUTE_URL);

        $client = new QualliId($opt, []);

        // Allow modifications on the OAuth2 client using event listeners or event subscribers
        $event = new CreateOAuth2ProviderEvent($request, $client, $opt);
        $this->eventDispatcher->dispatch($event);

        return $event->getClient();
    }
}
