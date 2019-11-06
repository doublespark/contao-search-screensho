<?php

namespace Doublespark\ContaoSearchScreenshot\Library;

class PhantomJs {

    /**
     * The path to phantomJs
     * @var string
     */
    protected $phantomJs = 'vendor/bin/phantomjs';

    /**
     * @param $path
     * @throws \Exception
     */
    public function setPhantomJsPath($path)
    {
        if(!file_exists($path) || !is_executable($path)) {
            throw new \Exception(sprintf('PhantomJs file does not exist or is not executable: %s', $path));
        }

        $this->phantomJs = $path;
    }

    /**
     * Capture screenshot of URL
     * @param string $url
     * @param CaptureConfig $config
     * @param string $savePath
     * @return array
     * @throws \Exception
     */
    public function capture(string $url, CaptureConfig $config, string $savePath)
    {
        // Escape backslashes in windows path directories
        $savePath = str_replace('\\', '\\\\', $savePath);

        if(!is_writable(dirname($savePath))) {
            throw new \Exception(sprintf('Path is not writeable by PhantomJs: %s', $savePath));
        }

        $file = $this->writeScript($url, $config, $savePath);

        $cmd    = escapeshellcmd(sprintf("%s --ignore-ssl-errors=true --ssl-protocol=any --web-security=no %s", $this->phantomJs, $file));
        $result = shell_exec($cmd);

        $this->removeScript($file);

        return $this->parseResult($result);
    }

    /**
     * Parse JSON result from command
     * @param $data
     * @return array
     */
    protected function parseResult($data)
    {
        // Data is invalid
        if($data === null || !is_string($data)) {
            return [];
        }

        // Not a JSON string
        if(substr($data, 0, 1) !== '{') {
            return [];
        }

        // Return decoded JSON string
        return (array) json_decode($data, true);
    }

    /**
     * Write the script that the command will run
     * @param string $url
     * @param CaptureConfig $config
     * @param string $savePath
     * @return bool|string
     * @throws \Exception
     */
    protected function writeScript(string $url, CaptureConfig $config, string $savePath)
    {
        $file = tempnam('/tmp', 'phantomjs');

        // Could not create tmp file
        if(!$file || !is_writable($file)) {
            throw new \Exception('Could not create tmp file on system. Please check your tmp directory and make sure it is writeable.');
        }

        $contents = "var page = require('webpage').create();
                    var response = { status:0 };          
                    page.settings.resourceTimeout = 4000;
                    page.customHeaders = { 'User-Agent': 'Mozilla/5.0 (X11; Linux i686; rv:64.0) Gecko/20100101 Firefox/64.0' };
                    page.onResourceTimeout = function(e) {                    
                           response 		= e;
                           response.status = e.errorCode;
                    };
                    page.onResourceError = function(e) {
                        if(response.status != 200)
                        {
                            response 		= e;
                            response.status = e.errorCode;
                        }
                    };                    
                    page.onResourceReceived = function (r) {
                        if(!response.status) response = r;
                    };
                    page.clipRect = {
                        top: 0,
                        left: 0,
                        width: ".$config->getViewportWidth().",
                        height: ".$config->getViewportHeight()."
                    };
                    page.viewportSize = {
                         width: ".$config->getViewportWidth().",
                         height: ".$config->getViewportHeight().",
                    };
                    page.onError = function(msg){
                        console.log(msg);
                    }
                    page.open('".$url."', function(status) {                       
                        if(status === 'success')
                        {
                            setTimeout(function() {
                                page.render('".$savePath."');
                                console.log(JSON.stringify(response));
                                phantom.exit();
                            }, 2000);
                        }
                        else
                        {
                            console.log(JSON.stringify(response));
                            phantom.exit();
                        }               
                    });";

        // Could not write script data to tmp file
        if(file_put_contents($file, $contents) === false) {
            $this->removeScript($file);
            throw new \Exception(sprintf('Could not write data to tmp file: %s. Please check your tmp directory and make sure it is writeable.', $file));
        }

        return $file;
    }

    /**
     * Remove the script file
     * @param $file
     */
    protected function removeScript($file)
    {
        if($file && file_exists($file)) {
            unlink($file);
        }
    }
}