<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin\ButtonGenerator;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Nistech\ContaoQualliIdLogin\OAuth2\Client\QualliIdBackendClientFactory;
use Nistech\ContaoQualliIdLogin\OAuth2\Client\QualliIdFrontendClientFactory;
use Markocupic\ContaoOAuth2Client\ButtonGenerator\ButtonGeneratorInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class BackendButtonGenerator implements ButtonGeneratorInterface
{
    public function __construct(
        private readonly ScopeMatcher $scopeMatcher,
        private readonly RequestStack $requestStack,
        private readonly Environment $twig,
    ) {
    }

    /**
     * Return all supported oauth clients
     * e.g. ['qualliid_backend','qualliid_frontend'].
     *
     * @return array<string>
     */
    public function supports(): array
    {
        return [QualliIdFrontendClientFactory::NAME];
    }

    public function renderButton(string $clientName): string
    {
        return $this->twig->render(
            '@NistechContaoQualliIdLogin/components/_login_qualliid.html.twig',
            [
                'client_name' => $clientName,
            ]
        );
    }
}
