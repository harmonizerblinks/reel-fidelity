<?php

/* 
 * WebResource
 * Loads, compiles and outputs web resource files to limit bandwidth latency 
 * WARNING: This controller does not play well with custom font related css files and images yet. 
 * Those should be linked directly with <link ..> tag.
 */
class WebResource{

    const DELIMITER = ",";

    private $files;
    private $rootUrl;
    private $rootPath;
    private $content = "";
    private $key;
    private $cacheDir;
    private $disableCaching = false;
    private $salt;

    public function __construct() {
        global $gGet;
        if (empty($gGet['f'])) {
            header("Pragma: cache");
            header("Content-type: text/plain; charset: UTF-8");
            throw new Exception('Required parameter "f" not set');
        }
        $this->files = explode(self::DELIMITER, $gGet['f']);
        $this->rootUrl = (empty($gGet['r'])) ? AppConfig::ROOT_URL : $gGet['r'];
        $this->rootPath = (empty($gGet['rp'])) ? dirname(__DIR__) : $gGet['rp'];
        $this->cacheDir = $this->rootPath . DIRECTORY_SEPARATOR . "cache";
        $this->disableCaching = AppConfig::DISABLE_ASSETS_CACHING;
        $this->salt = Utils::a($gGet, 'salt');
        $this->key = md5(trim($gGet['f']) . $this->salt);

    }

    public function js() {
        $root = $this->rootPath;
        $rootUrl = $this->rootUrl;
        $files = $this->files;
        $cacheFile = $this->cacheDir . DIRECTORY_SEPARATOR . $this->key . '.js';
        $filePaths = array();
        $content = '';

        if (!$this->disableCaching) {
            if (file_exists($cacheFile)) {
                $this->outputResource(file_get_contents($cacheFile), 'js');
                echo sprintf("/**served from %s*/", $this->key);
                return;
            }
        }

        $urls = array();
        $notFounds = array();
        $illegals = array();

        if (!empty($files)) {

            foreach ($files as $file) {
                $file = trim($file);
                if (!Utils::endsWith($file, '.js')) {
                    $illegals[] = $file;
                    continue;
                }
                $fileFound = realpath($root . DIRECTORY_SEPARATOR . $file);
                if ($fileFound) {
                    $filePaths[] = $fileFound;
                    $urls[] = $rootUrl . "/" . $file;
                } else {
                    $notFounds[] = $rootUrl . "/" . $file;
                }
            }

            foreach ($filePaths as $key => $path) {
                $content .= "\n\n/**------ {$urls[$key]}-----------------*/\n\n";
                $content .= file_get_contents($path);
                $content .= "\n\n/**-------------------end of {$urls[$key]}------------*/\n\n";
            }
            if (!empty($notFounds)) {
                $content .= "\n\n/**The following recources were not found: \n\n" . implode("\n", $notFounds) . "\n\n*/";
            }
            if (!empty($illegals)) {
                $content .= "\n\n/**The following recources were illegal: \n\n" . implode("\n", $illegals) . "\n\n*/";
            }
            $this->content = $content;

            if (!$this->disableCaching) {
                $this->saveInCache($content, $this->key, 'js');
            }

            $this->outputResource($content, 'js');
            echo "/*served from fs*/";
        }
    }

    function css() {
        $root = $this->rootPath;
        $rootUrl = $this->rootUrl;
        $files = $this->files;
        $cacheFile = $this->cacheDir . DIRECTORY_SEPARATOR . $this->key . '.css';
        $filePaths = array();
        $content = '';

        if (!$this->disableCaching) {
            if (file_exists($cacheFile)) {
                $this->outputResource(file_get_contents($cacheFile), 'css');
                echo sprintf("/**served from %s*/", $this->key);
                return;
            }
        }
        $urls = array();
        $notFounds = array();
        $illegals = array();

        if (!empty($files)) {

            foreach ($files as $file) {
                $file = trim($file);
                if (!Utils::endsWith($file, '.css')) {
                    $illegals[] = $file;
                    continue;
                }
                $fileFound = realpath($root . DIRECTORY_SEPARATOR . $file);
                if ($fileFound) {
                    $filePaths[] = $fileFound;
                    $urls[] = $rootUrl . "/" . $file;
                } else {
                    $notFounds[] = $rootUrl . "/" . $file;
                }
            }

            foreach ($filePaths as $key => $path) {
                $content .= "\n\n/**------ {$urls[$key]}-----------------*/\n\n";
                $content .= file_get_contents($path);
                $content .= "\n\n/**-------------------end of {$urls[$key]}------------*/\n\n";
            }
            if (!empty($notFounds)) {
                $content .= "\n\n/**The following recources were not found: \n\n" . implode("\n", $notFounds) . "\n\n*/";
            }
            if (!empty($illegals)) {
                $content .= "\n\n/**The following recources were illegal: \n\n" . implode("\n", $illegals) . "\n\n*/";
            }
            $this->content = $content;
            if (!$this->disableCaching) {
                $this->saveInCache($content, $this->key, 'css');
            }
            $this->outputResource($content, 'css');
            echo "/*served from fs*/";
        }
    }
    private function outputResource($content, $type) {
        
             
        header("Pragma: cache");
        header("Cache-Control: max-age=29030400, public");        
        $offset = 60 * 60 * 24 * 365;
        $expStr = "Expires: ".gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
        header($expStr);
        
        switch ($type) {
            case 'js':
                header("Content-type: text/javascript; charset: UTF-8");
                echo $content;
                break;
            case 'css':
                header("Content-type: text/css; charset: UTF-8");
                echo $content;
                break;
        }
    }

    private function saveInCache($content, $key, $type) {
        $filename = $this->cacheDir . DIRECTORY_SEPARATOR . $key . "." . $type;
        file_put_contents($filename, $content);
    }

}
