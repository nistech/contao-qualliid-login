<?php

declare(strict_types=1);

/*
 * This file is part of Contao GitHub Login.
 *
 * (c) Marko Cupic 2024 <m.cupic@gmx.ch>
 * @license GPL-3.0-or-later
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 * @link https://github.com/nistech/contao-qualli.id-client
 */

namespace NistechContaoQualliIdLogin\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use NistechContaoQualliIdLogin\NistechContaoQualliIdLogin;
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
