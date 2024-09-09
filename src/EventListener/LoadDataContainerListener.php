<?php
namespace Lukasbableck\ContaoTitleDescriptionBundle\EventListener;

use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;

#[AsHook('loadDataContainer')]
class LoadDataContainerListener {
	public function __invoke(string $table): void {
		if (\array_key_exists('pageTitle', $GLOBALS['TL_DCA'][$table]['fields'])) {
			$GLOBALS['TL_DCA'][$table]['fields']['pageTitle']['wizard'] = [WizardListener::class, '__invoke'];
		}
		if (\array_key_exists('description', $GLOBALS['TL_DCA'][$table]['fields'])) {
			$GLOBALS['TL_DCA'][$table]['fields']['description']['wizard'] = [WizardListener::class, '__invoke'];
		}
	}
}
