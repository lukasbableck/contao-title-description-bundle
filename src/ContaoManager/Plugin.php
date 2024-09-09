<?php
namespace Lukasbableck\ContaoTitleDescriptionBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Lukasbableck\ContaoTitleDescriptionBundle\ContaoTitleDescriptionBundle;

class Plugin implements BundlePluginInterface {
	public function getBundles(ParserInterface $parser): array {
		return [BundleConfig::create(ContaoTitleDescriptionBundle::class)->setLoadAfter([ContaoCoreBundle::class])];
	}
}
