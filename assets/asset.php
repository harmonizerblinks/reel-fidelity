<?php
require_once dirname(__DIR__) . '/conf/AppConfig.class.php';
require_once dirname(__DIR__) . '/src/Startup.class.php';
require_once dirname(__DIR__) . '/src/WebResource.class.php';

$wr = new WebResource();
$type = $gGet['t'];
switch($type){
    case 'js':
        $wr->js();
        break;
    case 'css':
        $wr->css();
        break;    
}



?>