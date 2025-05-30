<?php

declare(strict_types=1);

namespace Nistech\ContaoQualliIdLogin\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Nistech\ContaoQualliIdLogin\NistechContaoQualliIdLogin;
use Markocupic\ContaoOAuth2Client\MarkocupicContaoOAuth2Client;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(NistechContaoQualliIdLogin::class)
                ->setLoadAfter([
                    ContaoCoreBundle::class,
                    MarkocupicContaoOAuth2Client::class,
                ]),
        ];
    }
}
