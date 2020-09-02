<?php

/*
 * Handle ajax calls from UI
 *
 */
require_once 'Startup.class.php';
require_once 'Reel.class.php';
require_once 'Rates.Class.php';
require_once 'Social.class.php';
require_once 'fidelity_rate_reader2.php';

// execute the query silently
$root = dirname(dirname(__FILE__));
$storage = $root.DIRECTORY_SEPARATOR.'updates'.DIRECTORY_SEPARATOR;
try{

  // when should we run this query
  // Check the last time that this files where read rom the website
  $sample = $storage.'forex-rates.json';
  $should_read = true;

  if(file_exists($sample)){
	  $last_modified = filemtime($sample);
	  if($last_modified !== false){
		  $now = time();
		  $today = new DateTime('@'.$now);
		  $then = new DateTime('@'.$last_modified);
		  $diff = $today->diff($then);
		  $min = (int)$diff->i;
		  if($min < 15){
			  $should_read = false;
		  }
	  }
  }


  if($should_read){
	  $data = fetch_fidelity_rates();
  if($data){
    foreach($data as $key_type => $maps){
    	$parts = explode(' ', $key_type);
    	// select the first two words
    	$groups = array();
    	$groups[] = array_shift($parts);
    	$groups[] = array_shift($parts);
    	$key_name = implode('-', array_map('strtolower', $groups));
    	// $key_name = str_replace(' ','-', strtolower($key_type));
    	$file_name = "{$storage}{$key_name}.json";
    	$collections = array('type' => $key_type, 'rates' => $maps);
    	file_put_contents($file_name, json_encode($collections));
    }

  }
  }

}catch(Exception $ex){
  // ignore this
}

class AjaxAction {

   public static function getRates(){

        $raw = Utils::a($_GET, 'raw');

        $r = new Rates();

        // $rates = $r->getDailyIndicativeRatesUpdated();
        $rates = $r->getFXRates();
        if($raw){
            echo json_encode($rates);

        }else{
            // $rates['type'] = 'FX Rates';
            // self::buildRatePage($rates);
            echo Rates::buildFXRatesTable($rates);
        }

        return;

    }
    public static function buildRatePage($data){
      $view = null;
      if(!$data){
        return;
        // $view = $rdt->buildViewableFXRatesTable($data);
      }

      $rdt = new Rates();

      $view = $rdt->buildViewableFXRatesTable($data);
$doc =<<< EOD
  <div style="background-color:#000;
  z-index:1000;color:#fff;" class="dynamic-slides Stanbic-bank">
  <br/>
  <center><img src="assets/img/fidelity_logo.png" /></center>

      	<div class="slide-bgs">
        {$view}
      		</div>
      	</div>
      </div>
EOD;

      file_put_contents(dirname(__DIR__).'/slides/h22.html', $doc);
      file_put_contents(dirname(__DIR__).'/slides/h23.html', $doc);
      file_put_contents(dirname(__DIR__).'/slides/h24.html', $doc);
      file_put_contents(dirname(__DIR__).'/slides/h25.html', $doc);
      // file_put_contents(dirname(__DIR__).'/slides/h34.html', $doc);
      return;
    }

    public static function buildInterestRatePage($data){
      $view = null;
      if(!$data){
        return;
      }

      $rdt = new Rates();

      $view = $rdt->buildInterestRatesTable($data);
$doc =<<< EOD
  <div style="background-color:#000;
  z-index:100000;color:#fff;" class="dynamic-slides Stanbic-bank">
  <br/>
  <center><img src="assets/img/fidelity_logo.png" /></center>
      	<div class="slide-bgs">
        {$view}
      		</div>
      	</div>
      </div>
EOD;

      file_put_contents(dirname(__DIR__).'/slides/h23.html', $doc);
      return;
    }

    public static function buildFixedDepositRatePage($data){
      $view = null;
      if(!$data){
        return;
      }

      $rdt = new Rates();

      $view = $rdt->buildFixedDepositRatesTable($data);
$doc =<<< EOD
  <div style="background-color:#000;
  z-index:100000;color:#fff;" class="dynamic-slides Stanbic-bank">
  <br/>
  <center><img src="assets/img/fidelity_logo.png" /></center>

      	<div class="slide-bgs">
        {$view}
      		</div>
      	</div>
      </div>
EOD;

      file_put_contents(dirname(__DIR__).'/slides/h24.html', $doc);
      return;
    }

    public static function buildDailyIndicativeRatePage($data){
      $view = null;
      if(!$data){
        return;
      }

      $rdt = new Rates();

      $view = $rdt->buildDailyIndicativeRatesTable($data);
    $doc =<<< EOD
    <div style="background-color:#000;
    z-index:100000;color:#fff;" class="dynamic-slides Stanbic-bank">
    <br/>
    <center><img src="assets/img/fidelity_logo.png" /></center>

        <div class="slide-bgs">
        {$view}
          </div>
        </div>
      </div>
EOD;

      file_put_contents(dirname(__DIR__).'/slides/h25.html', $doc);
      return;
    }

    public static function getScrollingRates(){

         $raw = Utils::a($_GET, 'raw');

         $r = new Rates();

         $rates = $r->getFXRates();
         $irates =$r->getInterestRates();
         $frates = $r->getFixedDepositRates();
         $drates = $r->getDailyIndicativeRates();

         if($raw){
             echo json_encode($rates);

         }else{
             // $rates['type'] = 'FX Rates';
             self::buildRatePage($rates);
             self::buildInterestRatePage($irates);
             self::buildFixedDepositRatePage($frates);
             self::buildDailyIndicativeRatePage($drates);

             echo Rates::buildNewRatesMarquee($rates);
         }

         return;

     }

    public static function getInterestRates1(){

        $raw = Utils::a($_GET, 'raw');

        $r = new Rates();

        $rates = $r->getInterestRates();

        if($raw){
            echo json_encode($rates);

        }else{
            echo Rates::buildInterestRatesMarquee($rates);
        }

        return;

    }
    public static function getDailyIndicativeRates(){

        $raw = Utils::a($_GET, 'raw');

        $r = new Rates();

        $rates = $r->getDailyIndicativeRates();
        if(!$rates){
          return 'No daily indicative rate data to show';
        }

        if($raw){
            echo json_encode($rates);

        }else{
            echo Rates::buildDailyIndicativeRatesTable($rates);
        }

        return;

    }
    public static function getInterestRates(){

        $raw = Utils::a($_GET, 'raw');

        $r = new Rates();

        $rates = $r->getInterestRates();
        if(!$rates){
          return 'No interest rate data to show';
        }

        if($raw){
            echo json_encode($rates);
        }else{
            echo Rates::buildInterestRatesTable($rates);
        }
        return;
    }

    public static function getAllInterestRates(){

        $raw = Utils::a($_GET, 'raw');

        $r = new Rates();

        $rates = $r->getInterestRates();

        if($raw){
            echo json_encode($rates);

        }else{
            echo Rates::buildInterestRatesTable($rates);
        }

        return;

    }

    public static function getGovTreasuryRates(){
      $raw = Utils::a($_GET, 'raw');

      $r = new Rates();
      $rates = $r->getGovermentTreasury();

      if($raw){
          echo json_encode($rates);

      }else{
          echo Rates::buildGovermentTreasuryRateTable($rates);
      }

      return;

    }

    public static function getTransferRates(){
      $raw = Utils::a($_GET, 'raw');

      $r = new Rates();
      $rates = $r->getTransferRates();

      if($raw){
          echo json_encode($rates);

      }else{
          echo Rates::buildTransferRatesTable($rates);
      }

      return;

    }

    // Get deposit rates
    public static function getFixedDepositRates(){
      $raw = Utils::a($_GET, 'raw');

      $r = new Rates();
      $rates = $r->getFixedDepositRates();

      if(!$rates){
        return 'No fixed deposit rate data to show';
      }

      if($raw){
          echo json_encode($rates);

      }else{

          echo Rates::buildFixedDepositRatesTable($rates);
      }

      return;

    }

    public static function getBaseRates(){
      $r = new Rates();
      $rates = $r->getBaseRates();

      if($rates){
        echo json_encode($rates);
      }else{
        return null;
      }

    }
    public static function getAllRates(){

        $raw = Utils::a($_GET, 'raw');

        $r = new Rates();
        // $rates = $r->get();
        $rates = $r->getDailyIndicativeRatesUpdated();
        $data = array();

        if($raw){
            echo json_encode($rates);

        }else{
            echo Rates::buildRatesTable($rates);
        }

        return;

    }
    public static function getTabbedAllRates(){
        $r = new Rates();
        $rates[] = array('title' => 'Exchange Rates', 'content' => Rates::buildRatesTable($r->get()));
        $rates[] = array('title' => 'Interest Rates', 'content' => Rates::buildInterestRatesTable($r->getInterestRates()));
        echo Rates::buildRatesTabs($rates);
    }


    public static function getSocialFeeds(){
         $raw = Utils::a($_GET, 'raw');
         $feeds = Social::getTwitterFeeds();

         if($raw){
            echo json_encode($feeds);
         } else{
            echo Social::buildTwitterFeedsMarquee($feeds);
         }

    }

    public static function getAllSocialFeeds(){

        $raw = Utils::a($_GET, 'raw');
         $feeds = Social::getTwitterFeeds();

         if($raw){
            echo json_encode($feeds);
         } else{
            echo Social::buildTwitterFeedsTable($feeds);
         }

    }

    public static function send_site_comment() {
        $status = 'OK';
        $msg = '';
        $response = array('status' => $status, 'msg' => $msg, 'forward' => null);

        try {
            $post_data = filter_input_array(INPUT_POST);

            if (isset($post_data['type'])) {
                throw new Exception('Field named "type" not permitted. Choose a'
                    . ' different name for the field.');
            }

            $post_data['type'] = 'sitecomment';

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            if (!$email) {
                throw new Exception("Invalid email specified");
            }

            if (!$post_data['name']) {
                throw new Exception("Name is required");
            }

            if (!$post_data['message']) {
                throw new Exception("Message is required");
            }

            $post_data['message'] = nl2br($post_data['message']);

                //Send registration details to site owner by email
            require_once __DIR__ . '/MailUtils.class.php';

            ob_start();
            require ROOT_PATH . '/templates/emails/site_comment.php';
            $template_content = ob_get_clean();
            $template_content = Utils::parseTemplate($template_content, $post_data);

            MailUtils::quickSend(
                AppConfig::RELATIONSHIP_MANAGER_EMAIL
                , $post_data['subject']
                , $template_content
                , null
                , null
                , AppConfig::CC_EMAIL
                );

            try {

                    //Initialize DB library
                DB::init();

                    //Convert user input to a 'sitecomment' bean
                $site_comment = DB::graph($post_data);

                    //Save 'sitecomment' bean
                DB::store($site_comment);
            } catch (Exception $ex) {

            }

            $msg .= 'Thank you! Your message was sent successfully. We will get in touch with '
            . 'you soonest.';

        } catch (Exception $e) {
            $status = 'ERROR';
            $msg = $e->getMessage() . ' Please try again.';
        }

        $response['status'] = $status;
        $response['msg'] = $msg;


        echo json_encode($response, JSON_FORCE_OBJECT);
    }

}

$action = null;
if ($action = filter_input(INPUT_GET, 'action')) {
    AjaxAction::$action();
}
