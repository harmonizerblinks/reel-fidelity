<?php

require_once dirname(__DIR__) . '/conf/AppConfig.class.php';
require_once dirname(__DIR__) . '/src/Startup.class.php';
require_once dirname(__DIR__) . '/src/Reel.class.php';

$pl_name = Utils::a( $gGet, 'playlist', $_SERVER['REMOTE_ADDR'] );
$pl = Utils::getPlaylistForIP($pl_name, true);
$reel = new Reel();
$reel->setPlaylist($pl);
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0,
    maximum-scale=1.0, user-scalable=0" />

    <title>
        Rates | <?php echo AppConfig::SITE_TITLE; ?>
    </title>

    <link rel="stylesheet" type="text/css" href="../bootstrap/css/bootstrap.min.css"  />
    <link rel="stylesheet" type="text/css" href="../assets/css/responsive.css"  />
    <link rel="stylesheet" type="text/css" href="../assets/css/fonts.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/plugins.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/animate.min.css" />
    <link rel="stylesheet" type="text/css" href="../assets/css/main.css"  />
    <link rel="stylesheet" type="text/css" href="assets/css/main.css"  />
    <?php $reel->renderStyles(); ?>
        <!--[if IE 7]>
        <link rel="stylesheet"
          href="../assets/css/fontawesome/font-awesome-ie7.min.css">
          <![endif]-->
        <!--[if IE 8]>
    <link href="../assets/css/ie8.css" rel="stylesheet" type="text/css"/>
    <![endif]-->
        <!--[if lt IE 9]>
        <script src="../assets/js/libs/html5shiv.js"></script>
        <![endif]-->
		<style type="text/css">
		body{
			background:#000;
		}
		</style>
        <script type="text/javascript" src="../assets/js/libs/jquery.min.js"></script>
    </head>

    <body class="rates">
        <div class="wrapper" id="wrapper">
		<div class="slide-bgs">
		<br/>
                    <center><img src="../assets/img/fidelity_logo.png" /></center>
					</br>
                </div>
            <div style="background: #000;" class="dynamic-slide">

                <div class="rates_container container">
                    <span id="rates"><em></em></span>
                    <div style="background: #000;display:none;" id="feeds"><em>Loading feeds...</em></div>
                    <div style="background: #000;"
					     data-status="0"
						 id="all_rates1" class="cctx container">
						 <div id="all_rates_data1"></div>
                         <div style="display:block;font-weight:bold;font-size:18px;text-align:right;"
                         id="all_rates_base_data1"></div>
						 </div>
					<div style="background: #000;display:none;"
					     data-status="0"
						 id="all_gov_rates" class="cctx container">
						 <div id="all_gov_rates_data"></div>
                         <div style="display:block;font-weight:bold;font-size:18px;text-align:right;"
                              id="all_gov_rates_base_data"></div>
						 </div>
             <div style="background: #000;"
        data-status="0"
      id="all_rates2" class="cctx container">
      <div id="all_rates_data2"></div>
                  <div style="display:block;font-weight:bold;font-size:18px;text-align:right;"
                  id="all_rates_base_data2"></div>
      </div>
					<div style="background: #000;display:none;"
					     data-status="0"
						 id="all_deposit_rates" class="cctx container">
						 <div id="all_deposit_rates_data"></div>
                         <div style="display:block;font-weight:bold;font-size:18px;text-align:right;"
                              id="all_deposit_rates_base_data"></div>
						 </div>
             <div style="background: #000;"
        data-status="0"
      id="all_rates3" class="cctx container">
      <div id="all_rates_data3"></div>
                  <div style="display:block;font-weight:bold;font-size:18px;text-align:right;"
                  id="all_rates_base_data3"></div>
      </div>
					<div style="background: #000;display:none;"
					     data-status="0"
						 id="all_transfer_rates" class="cctx container">
						 <div id="all_transfer_rates_data"></div>
                         <div style="display:block;font-weight:bold;font-size:18px;text-align:right;"
                              id="all_transfer_rates_base_data"></div>
						 </div>
                </div>
            </div><!-- .dynamic-slide -->
            <div class="touch-icon">
			<img style="display:none;"width="68" height="68" data-src="../assets/img/touch-icon.png"
			     src="../assets/img/access_qrcode.png" /></div>
        </div><!-- #wrapper -->

        <script type="text/javascript" src="../bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="../assets/js/libs/breakpoints.js"></script>
        <script type="text/javascript" src="../assets/plugins/respond/respond.min.js"></script>
        <script type="text/javascript" src="../assets/plugins/marquee/jquery.marquee.min.js"></script>
        <script type="text/javascript" src="../assets/js/app.js"></script>
        <script type="text/javascript" src="../assets/js/plugins.js"></script>

        <script>
            $(document).ready(function() {
                App.init();
                Plugins.init();
            });
            <?php echo $reel->renderOptions(); ?>
        </script>

        <script type="text/javascript" src="assets/js/reel-rates.js"></script>
</body>
</html>
