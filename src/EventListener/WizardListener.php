<?php
namespace Lukasbableck\ContaoTitleDescriptionBundle\EventListener;

use Contao\DataContainer;

class WizardListener {
	public function __invoke(DataContainer $dc): string {

		return 'You\'re a wizard, Harry!';
	}
}
