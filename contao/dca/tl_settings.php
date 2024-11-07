<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_settings']['fields']['titleDescriptionSpecialChars'] = [
	'inputType' => 'text',
	'eval' => ['maxlength' => 255, 'tl_class' => 'w50'],
];

PaletteManipulator::create()
	->addField('titleDescriptionSpecialChars', 'backend_legend', PaletteManipulator::POSITION_APPEND)
	->applyToPalette('default', 'tl_settings')
;
