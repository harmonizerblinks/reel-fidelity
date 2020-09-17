<?php

/*
 * Bootstraps the website by loading required startup resources
 * and checking parameters.
 * 
 */
class Startup {

    public function __construct(){}
    
    //Start a session if one has not been started yet
    public static function run() {
        if (!session_id()) {
            session_start();
        }

        //Load configuration file
        require_once dirname(__DIR__) . DIRECTORY_SEPARATOR
                . 'conf' . DIRECTORY_SEPARATOR . 'AppConfig.class.php';
        
        //Load DB library
        /*require_once __DIR__ . DIRECTORY_SEPARATOR . 'DB.class.php';*/
        
        //Load Utilities
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Utils.class.php';

        Startup::loadPostGet();
                
    }

    public static function loadPostGet() {

         //Load Utilities
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'Validator.class.php';

        global $gGet, $gPost;

        $gGet = $gPost = array();

        if (!empty($_GET)) {
            foreach ($_GET as $key => $val) {
                if (!is_numeric($val))
                    $gGet[$key] = Validator::escapeCodes($val);
                else
                    $gGet[$key] = Validator::escapeCodes($val);
            }
        }
        if (!empty($_POST)) {
            foreach ($_POST as $key => $val) {
                if (!is_numeric($val))
                    $gPost[$key] = Validator::escapeCodes($val);
                else
                    $gPost[$key] = $val;
            }
        }
        
    }



}

//Startup the application
Startup::run();
