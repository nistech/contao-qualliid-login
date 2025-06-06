<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin\EventSubscriber;

use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

readonly class KernelRequestSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private ScopeMatcher $scopeMatcher,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => 'loadAssets'];
    }

    public function loadAssets(RequestEvent $e): void
    {
        $request = $e->getRequest();

        if ($this->scopeMatcher->isBackendRequest($request)) {
            if ('contao_backend_login' === $request->attributes->get('_route')) {
                $GLOBALS['TL_CSS'][] = 'bundles/nistech/contaowithqualliidlogin/css/login_button.css|static';
            }
        }
    }
}
