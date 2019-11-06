<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Cron jobs
 */
$GLOBALS['BE_MOD']['system']['search_screenshots'] = ['callback'=> 'Doublespark\ContaoSearchScreenshot\BackendModules\ModuleSearchScreenshots'];

$GLOBALS['TL_PURGE']['folders']['search_screenshots'] = [
    'callback' => ['Doublespark\ContaoSearchScreenshot\Cron\Automator', 'purgeScreenshots'],
    'affected' => ['web/bundles/contaosearchscreenshot/img']
];

/**
 * Default configuration
 */
$GLOBALS['TL_CONFIG']['phantomjs_path']        = '/vendor/bin/phantomjs';
$GLOBALS['TL_CONFIG']['screenshot_viewport_w'] = 1600;
$GLOBALS['TL_CONFIG']['screenshot_viewport_h'] = 850;