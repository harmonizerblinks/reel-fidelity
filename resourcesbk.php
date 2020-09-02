<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';

$errors = array();
$messages = array();
// List the resources
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0,maximum-scale=1.0, user-scalable=0" />

    <title>Media Resource Panel</title>

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
  <style type="text/css">
  .item-update-list{
    display:block;
    margin:0px;
    padding: 0px;
  }
  .item-update-list li{
    display:inline-block;
    padding: 10px 10px;
    margin-right:5px;
    width:225px;
    margin-bottom:5px;
    text-align: center;
    font-weight: bold;
    border:1px solid #d8d8d8;
  }
  </style>
</head>
<body style="background-color:#fff;" class="homesss">
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:100%;">
                <h1 style="font-size:18;padding:3px;margin:3px;">Media Resource Panel</h1>
                <hr/>
                <ul class="item-update-list">
                  <li>
                    <a href="resource_lists.php"><i class="fa fa-cog"></i> Resource List</a>
                  </li>
                  <li>
                    <a style="text-align:center;" href="rate_form_update.php"><i class="fa fa-cog"></i> Resource Schedule</a>
                  </li>
                  <li>
                    <a href="rate_form_update.php"><i class="fa fa-cog"></i> Resource Upload</a>
                  </li>
                </ul>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
