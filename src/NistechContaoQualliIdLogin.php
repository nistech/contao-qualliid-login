<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin;

use Nistech\ContaoQualliIdLogin\DependencyInjection\NistechContaoQualliIdLoginExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NistechContaoQualliIdLogin extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function getContainerExtension(): NistechContaoQualliIdLoginExtension
    {
        return new NistechContaoQualliIdLoginExtension();
    }

    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
