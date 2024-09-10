<?php
namespace Lukasbableck\ContaoTitleDescriptionBundle\EventListener;

use Contao\DataContainer;

class PageTitleAttributesListener {
	public function __invoke(array $attributes, ?DataContainer $dc = null): array {
		return $attributes;
	}
}
