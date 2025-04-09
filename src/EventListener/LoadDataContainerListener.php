<?php
namespace Lukasbableck\ContaoTitleDescriptionBundle\EventListener;

use Contao\Config;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Contao\CoreBundle\DependencyInjection\Attribute\AsHook;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Symfony\Component\HttpFoundation\RequestStack;

#[AsHook('loadDataContainer')]
class LoadDataContainerListener {
	public function __construct(private RequestStack $requestStack, private ScopeMatcher $scopeMatcher) {
	}

	public function __invoke(string $table): void {
		if (!\array_key_exists($table, $GLOBALS['TL_DCA']) || !\array_key_exists('fields', $GLOBALS['TL_DCA'][$table])
			|| (!\array_key_exists('pageTitle', $GLOBALS['TL_DCA'][$table]['fields'])
			&& !\array_key_exists('description', $GLOBALS['TL_DCA'][$table]['fields']))) {
			return;
		}

		$request = $this->requestStack->getCurrentRequest();
		if (null == $request || !$this->scopeMatcher->isBackendRequest($request) || 'edit' !== $request->query->get('act')) {
			return;
		}
		$chars = Config::get('titleDescriptionSpecialChars');
		if (!empty($chars)) {
			$length = grapheme_strlen($chars);
			$arrChars = [];
			for ($i = 0; $i < $length; ++$i) {
				$arrChars[] = grapheme_substr($chars, $i, 1);
			}
			$GLOBALS['TL_DCA'][$table]['fields']['specialCharsPicker'] = [
				'label' => &$GLOBALS['TL_LANG']['MSC']['specialCharsPicker'],
				'inputType' => 'charPicker',
				'options' => $arrChars,
				'eval' => ['tl_class' => 'w50 special-chars'],
			];
			if (\array_key_exists('serpPreview', $GLOBALS['TL_DCA'][$table]['fields'])) {
				if (!\array_key_exists('eval', $GLOBALS['TL_DCA'][$table]['fields']['serpPreview'])) {
					$GLOBALS['TL_DCA'][$table]['fields']['serpPreview']['eval'] = [];
				}
				if (!\array_key_exists('tl_class', $GLOBALS['TL_DCA'][$table]['fields']['serpPreview']['eval'])) {
					$GLOBALS['TL_DCA'][$table]['fields']['serpPreview']['eval']['tl_class'] = '';
				}
				$GLOBALS['TL_DCA'][$table]['fields']['serpPreview']['eval']['tl_class'] .= ' clr';
			}
			if (!\is_array($GLOBALS['TL_DCA'][$table]['palettes'] ?? null)) {
				return;
			}
			foreach ($GLOBALS['TL_DCA'][$table]['palettes'] as $name => $palette) {
				if ('__selector__' === $name) {
					continue;
				}
				if (str_contains($palette, 'description')) {
					if ($GLOBALS['TL_DCA'][$table]['fields']['description']['eval']['noSpecialCharsPicker'] ?? false) {
						continue;
					}

					PaletteManipulator::create()
						->addField('specialCharsPicker', 'description', PaletteManipulator::POSITION_AFTER)
						->applyToPalette($name, $table)
					;
				}
			}
		}

		$GLOBALS['TL_CSS'][] = 'bundles/contaotitledescription/backend.css';
		$GLOBALS['TL_JAVASCRIPT'][] = 'bundles/contaotitledescription/backend.js';
	}
}
