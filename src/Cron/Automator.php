<?php

namespace Doublespark\ContaoSearchScreenshot\Cron;
use Contao\Backend;
use Contao\Config;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\Database;
use Contao\Folder;
use Doublespark\ContaoSearchScreenshot\Library\CaptureConfig;
use Doublespark\ContaoSearchScreenshot\Library\PhantomJs;
use Monolog\Logger;
use Psr\Log\LogLevel;

/**
* Provide methods to run automated jobs.
*/
class Automator extends Backend {

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Make the constuctor public
     */
    public function __construct()
    {
        parent::__construct();

        $this->logger = static::getContainer()->get('monolog.logger.contao');
    }

    /**
     * Update page screenshots
     */
    public function updateScreenshots()
    {
        $lastUpdated = time() - (3600 * 24 * 7);

        $objPages = Database::getInstance()->prepare('SELECT id,url FROM tl_search WHERE screenshot_last_updated < ?')->limit(5)->execute($lastUpdated);

        while($objPages->next())
        {
            $imgPath = $this->fetchScreenshot($objPages->url);
            Database::getInstance()->prepare('UPDATE tl_search SET screenshot=?, screenshot_last_updated=? WHERE id=?')->execute($imgPath, time(), $objPages->id);
        }

        // Purge search results
        $objFolder = new Folder('var/cache/prod/contao/search');
        $objFolder->purge();
    }

    /**
     * Update screenshots based on search index IDs
     * @param $arrIds
     * @throws \Exception
     */
    public function updateScreenshotsById($arrIds)
    {
        $objPages = Database::getInstance()->prepare('SELECT id,url FROM tl_search WHERE id IN(?)')->execute(implode(',',$arrIds));

        while($objPages->next())
        {
            $imgPath = $this->fetchScreenshot($objPages->url);
            Database::getInstance()->prepare('UPDATE tl_search SET screenshot=?, screenshot_last_updated=? WHERE id=?')->execute($imgPath, time(), $objPages->id);
        }
    }

    /**
     * Uses phantomJs to create a screenshot of a given URL
     * @param $pageUrl
     * @return null|string
     * @throws \Exception
     */
    protected function fetchScreenshot($pageUrl)
    {
        $phantomJs = new PhantomJs();
        $phantomJs->setPhantomJsPath(TL_ROOT.Config::get('phantomjs_path'));

        $config = new CaptureConfig();
        $config->setViewport(Config::get('screenshot_viewport_w'),Config::get('screenshot_viewport_h'));

        $filename = substr(md5($pageUrl), 0, 6);

        $savePath = 'bundles/contaosearchscreenshot/img/'.$filename.'.jpg';

        $result = [];

        try {
            $result = $phantomJs->capture($pageUrl, $config, TL_ROOT.'/web/'.$savePath);
        } catch(\Exception $e) {
            $this->logger->log(LogLevel::ERROR, 'Could not create screenshot: '.$e->getMessage(), array('contao' => new ContaoContext(__METHOD__, TL_ERROR)));
        }

        $arrAllowedStatus = [200,301,302];

        if(isset($result['status']) AND in_array($result['status'],$arrAllowedStatus))
        {
            $this->logger->log(LogLevel::INFO, 'Screenshot generated for '.$result['url'], array('contao' => new ContaoContext(__METHOD__, TL_CRON)));

            return $savePath;
        }
        else
        {
            if(isset($result['errorString']))
            {
                $this->logger->log(LogLevel::ERROR, 'Could not create screenshot: '.$result['errorString'], array('contao' => new ContaoContext(__METHOD__, TL_ERROR)));
                return;
            }
            else
            {
                if(isset($result['statusText']))
                {
                    $this->logger->log(LogLevel::ERROR, 'Could not create screenshot: '.$result['statusText'], array('contao' => new ContaoContext(__METHOD__, TL_ERROR)));
                }
                else
                {
                    if(file_exists(TL_ROOT.'/web/'.$savePath))
                    {
                        return $savePath;
                    }
                    else
                    {
                        $this->logger->log(LogLevel::ERROR, 'Could not create screenshot: Unknown error', array('contao' => new ContaoContext(__METHOD__, TL_ERROR)));
                    }
                }
            }

            return null;
        }
    }

    /**
     * Purge the screenshots
     */
    public function purgeScreenshots()
    {
        // Clear the screenshot info from the DB
        Database::getInstance()->query('UPDATE tl_search SET screenshot_last_updated=0, screenshot=NULL');

        // Purge the folder
        $objFolder = new Folder('web/bundles/contaosearchscreenshot/img');
        $objFolder->purge();

        // Add a log entry
        $this->logger->log(LogLevel::INFO, 'Purged the search results screenshots', array('contao' => new ContaoContext(__METHOD__, TL_CRON)));
    }
}