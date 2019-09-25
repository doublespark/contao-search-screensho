<?php

/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_page']['palettes']['regular'] =  str_replace
(
    'description;',
    'description;search_results_screenshot;',
	$GLOBALS['TL_DCA']['tl_page']['palettes']['regular']
);

// Fields
$GLOBALS['TL_DCA']['tl_page']['fields']['search_results_screenshot'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_page']['search_results_screenshot'],
    'exclude'   => true,
    'inputType' => 'fileTree',
    'eval'      => array('filesOnly'=>true, 'fieldType'=>'radio', 'mandatory'=>false, 'tl_class'=>'clr'),
    'sql'       => "binary(16) NULL"
);