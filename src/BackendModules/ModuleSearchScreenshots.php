<?php

namespace Doublespark\ContaoSearchScreenshot\BackendModules;
use Contao\BackendModule;
use Contao\Database;
use Contao\Input;
use Contao\System;
use Doublespark\ContaoSearchScreenshot\Cron\Automator;

class ModuleSearchScreenshots extends BackendModule {

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_search_screenshots';

    /**
     * Generate the module
     *
     * @throws \Exception
     */
    protected function compile()
    {
        if(Input::get('generate_screenshots') == 1)
        {
            $ids = Input::get('ids');

            if(!empty($ids))
            {
                $arrIds = explode(',',$ids);

                Database::getInstance()->prepare('UPDATE tl_search SET screenshot_last_updated=? WHERE id IN('.implode(',',$arrIds).')')->execute(0);
            }
        }

        $container = System::getContainer();
        $ref = $container->get('request_stack')->getCurrentRequest()->attributes->get('_contao_referer_id');

        $objSearchIndex = $this->Database->query('SELECT id, title, url, screenshot_last_updated, screenshot FROM tl_search');

        $this->Template->arrSearchIndex = $objSearchIndex->fetchAllAssoc();

        $this->Template->ref = $ref;
        $this->Template->href = $this->getReferer(true);
        $this->Template->title = 'Search result screenshots';
        $this->Template->button = $GLOBALS['TL_LANG']['MSC']['backBT'];
    }
}

class_alias(ModuleSearchScreenshots::class, 'ModuleSearchScreenshots');