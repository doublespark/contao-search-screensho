<?php

/**
 * Table tl_page
 */
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] =  str_replace
(
    '{search_legend:hide}',
    '{search_screenshot_legend:hide},phantomjs_path,screenshot_viewport_w,screenshot_viewport_h;{search_legend:hide}',
	$GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);

// Fields
$GLOBALS['TL_DCA']['tl_settings']['fields']['phantomjs_path'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['phantomjs_path'],
    'inputType' => 'text',
    'default'   => '/vendor/bin/phantomjs',
    'eval'      => array('mandatory'=>true, 'nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['screenshot_viewport_h'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['screenshot_viewport_h'],
    'inputType' => 'text',
    'default'   => 800,
    'eval'      => array('mandatory'=>true, 'rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50')
);

$GLOBALS['TL_DCA']['tl_settings']['fields']['screenshot_viewport_w'] = array(
    'label'     => &$GLOBALS['TL_LANG']['tl_settings']['screenshot_viewport_h'],
    'inputType' => 'text',
    'default'   => 1600,
    'eval'      => array('mandatory'=>true, 'rgxp'=>'natural', 'nospace'=>true, 'tl_class'=>'w50 clr')
);