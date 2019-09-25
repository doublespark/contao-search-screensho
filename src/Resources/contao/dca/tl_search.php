<?php

// Fields
$GLOBALS['TL_DCA']['tl_search']['fields']['screenshot'] = array(
    'sql' => "text NULL"
);

$GLOBALS['TL_DCA']['tl_search']['fields']['screenshot_last_updated'] = array(
    'sql' => "int(10) unsigned NOT NULL default '0'"
);