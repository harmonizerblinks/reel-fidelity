<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/ExcelParser.php';

$errors = array();
$messages = array();
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // get the content of the posted fields
  $rate_strings = $_POST['rates'];
  if(!$rate_strings || !strlen($rate_strings)){
    $errors[] = 'Please copy and paste the excelsheet content of the '.
    'combined rates for update';
  }

  if(!$errors){
    // we can process it
    try{

      ExcelParser::parseContent($rate_strings);
      // if we get this far we are done
      $messages[] = 'Rates parsed and written to folder /updates/';
    }catch(Exception $ex){
      $errors[] = $ex->getMessage();
    }
  }
}

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0,maximum-scale=1.0, user-scalable=0" />

    <title>RATES UPDATER</title>

    <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('bootstrap/css/bootstrap.min.css'); ?>"  />
    <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/responsive.css'); ?>"  />
    <link rel="stylesheet" type="text/css" href="assets/css/fonts.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/plugins.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/animate.min.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/plugins/onscreenkeyboard/jsKeyboard.css'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/main.css'); ?>"  />
    <!--[if IE 7]>
    <link rel="stylesheet"
      href="assets/css/fontawesome/font-awesome-ie7.min.css">
      <![endif]-->
    <!--[if IE 8]>
    <link href="<?php echo Utils::getAssetURL('assets/css/ie8.css');?>" rel="stylesheet" type="text/css"/>
    <![endif]-->

    <!--[if lt IE 9]>
    <script src="<?php //echo Utils::getAssetURL('assets/js/libs/html5shiv.js'); ?>"></script>
    <![endif]-->
</head>
<body style="background-color:#fff;" class="homesss">
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:100%;">
                <h1 style="font-size:18;padding:3px;margin:3px;">Rate Updater</h1>
                <hr/>
                <form action="rateupdater.php" method="POST">
                  <table cellpadding="10"
                         cellspacing="10"
                         border="0"
                         style="width:640px;margin-left:auto;margin-right:auto;">
                    <?php if($errors && count($errors)):?>
                      <tr>
                        <td>
                          <div class="alert alert-danger">
                            <?php echo implode(' ', $errors);?>
                          </div></td>
                      </tr>
                    <?php elseif($messages && count($messages)):?>
                      <tr>
                        <td>
                          <div class="alert alert-info">
                            <?php echo implode(' ', $messages);?>
                          </div></td>
                      </tr>
                    <?php endif;?>
                    <tr>
                      <td>
                        <div style="padding:10px;margin-bottom:10px;">
                          Copy and paste the contents of the combined
                          excelsheet then click on the update button
                        </div>
                        <textarea style="background:lightgreen;width:600px;height:320px;border:1px solid #d8d7d8;"
                                  id="rates"
                                  placeholder="Copy and paste the combined rates excel sheet contents..."
                                  name="rates"></textarea>
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td align="right">
                        <button style="margin-top:-10px;width:180px;height:45px;font-size:21px;">
                          <span style="font-size:18px;">Update Rates</span>
                        </button>
                    </tr>
                  </table>
                </form>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
