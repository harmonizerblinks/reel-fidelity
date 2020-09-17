<?php

    /**
    * Initialize Reel and set playlist
    *
    */
    require_once __DIR__.'/connection.php';
    require_once __DIR__ . '/conf/AppConfig.class.php';
    require_once __DIR__ . '/src/Startup.class.php';
    require_once __DIR__ . '/src/Reel.class.php';

    $pl_name = Utils::a( $gGet, 'playlist', $_SERVER['REMOTE_ADDR'] );
    $pl = Utils::getPlaylistForIP($pl_name);
    $reel = new Reel();
    $reel->setPlaylist($pl);
    
    $timer = 27000;
    $query = "SELECT * from rates_schedule_time";
    $result = queryData($query);
    if(!empty($result)){
        foreach($result as $re){
            $timer = $re['time'];
        }
    }


    //   $address = $_SERVER['REMOTE_ADDR'];
    //   if($address == '127.0.0.1' OR $address == "::1"){

          $jsonFiels = "./playlists/default.json";
    //   }else{
    //       $jsonFiels = "./playlists/$address";
    //   }

    function getContents2(){
        global $jsonFiels;
        $data = file_get_contents($jsonFiels,true);
        $data = json_decode($data,true);
        $slideData = $data['slides'];
        return $slideData;
    }
    ?>



<!DOCTYPE html>

    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0,
        maximum-scale=1.0, user-scalable=0" />

        <title><?php echo AppConfig::SITE_TITLE; ?></title>

        <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('bootstrap/css/bootstrap.min.css'); ?>"  />
        <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/responsive.css'); ?>"  />
        <link rel="stylesheet" type="text/css" href="assets/css/fonts.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/plugins.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/animate.min.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/plugins/onscreenkeyboard/jsKeyboard.css'); ?>" />
        <link rel="stylesheet" type="text/css" href="<?php echo Utils::getAssetURL('assets/css/main.css'); ?>"  />
        <?php $reel->renderStyles(); ?>
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
        <script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/libs/jquery.min.js');?>"></script>
        <script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/libs/modernizr.min.js');?>"></script>
        
        <style>
      
        .dynamic-slides{
          height: 98vh; 
          object-fit: cover;
          display: none;
          position: absolute;
          top: 0px;
          width: 100%;
        }
        .myModal{
            display:none;
            /* transition: 1s ease-in-out   */
        } 
        #simpleModal{
            position: fixed;
            z-index: 1000000;
            top:0;
            left:0;
            height: 100%;
            width: 100%;
            overflow: auto;
            background: rgba(0, 0, 0, 0.9)
        }
        .modal-elements{
            background: #fff;
            margin: 10% auto;
            padding: 40px 40px;
            width: 90%;
            box-shadow: 0 5px rgba(0, 0, 0, 0.2),7px 20px rgba(0, 0, 0, 0.17);
            min-height: 40vh;
        }
        .click-able-slide{
            margin-bottom: 12px;
            cursor: pointer;

        }
        .fidelity-bank-logo{
            position: absolute;
            bottom: 280px;
            right: 70px;
        }
        </style>
        
    </head>
    <body class="home <?php if($reel->getOptions()->disable_interaction){ echo 'no-interaction'; }?>">
        <input type="hidden" id="slider_time" value="<?php echo $timer ?>" >

        

        <div class="wrapper" id="wrapper">
            <div id="container">
                <div id="content padding-bottom-0px">

                


                    <!-- INTRO POPUP -->

                    <!-- END INTRO POPUP -->

                    <?php include 'common/toc.php'; ?>




                <!-- MAIN SLIDESHOW -->
                <div id="home-slider-wrap" >
                    <div id="home-slider" class="slider">
                        <div style="z-index:10000;" class="slider-nav-wrap">
                            <div style="background: #fc7c1f;" class="slider-nav container">
                                <div class="row" ><button class="min-max btn"><i class="icon icon-plus-sign"></i>  </button></div>
                                <!-- <div class="row">
                                    <button class="slider-menu btn"><i class="icon icon-folder-open"></i> <span>MENU</span></button>
                                </div>
                                <div class="row">
                                    <div class="btn-group btn-group-justified">
                                        <button class="prev btn" id='prev'><i class="icon icon-backward"></i></button>
                                        <button class="prev btn" id='pauseplay'><i class="icon icon-pause"></i></button>
                                        <button class="next btn" id="next"><i class="icon icon-forward"></i></button>
                                        <button class="volume btn" id="volume"><i class="icon icon-volume-up"></i></button>
                                        <button class="fullscreen btn"><i class="icon icon-resize-full"></i></button>
                                        <button class="refresh btn"><i class="icon icon-refresh"></i></button>
                                    </div>
                                </div> -->
                            </div>
                        </div>                    
                        <!-- SLIDESHOW OPTIONS -->
                        <!-- <div class="slides cycle-slideshow" id="slides"
                        data-cycle-pause-on-hover="false"
                        data-cycle-timeout ="<?php echo $timer ?>"
                        data-cycle-fx="fadeout"
                        data-cycle-swipe-fx="scrollHorz"
                        data-cycle-prev="#prev"
                        data-cycle-next="#next"
                        data-cycle-slides="> div"
                        > -->
                        <!-- END SLIDESHOW OPTIONS -->

                <?php 
                    $slidesArray = getContents2();
                   
                    foreach($slidesArray as $item){
                        $url= $item['url'];
                        $slides = file_get_contents($url);
                        echo $slides;
                      
                    }
                    ?>
                        <?php echo $reel->renderSlides(); ?>
                </div>
            </div>
            <!-- END MAIN SLIDESHOW -->

            

            <?php include  'common/contact-form.php'; ?>

        </div>
    </div><!-- #container -->

    <!-- AUDIO COMPONENT -->
    <audio id="player" autoplay="autoplay" >
        <source id="audio_source" type="audio/ogg" />
        <source id="audio_source_alt" type="audio/mpeg" />
    </audio>
    <!-- END AUDIO COMPONENT -->

    <!-- FOOTER -->
    <div style="background: #fc7c1f;" class="footer minimised" >
        <div id="rates_container">
            <div style="height:45px;
			padding:0px 4px;margin-top:-10px;
			font-size:30px;color:#000;" id="rates"><em>Loading rates...</em></div>
            <div class="clearfix"></div>
            <div id="all_rates" class=""><em>Loading rates...</em></div>
        </div>
        <div id="social_container">
            <div id="feeds"><em>Loading feeds...</em></div>
            <div class="clearfix"></div>
            <div id="all_feeds" class=""><em>Loading feeds...</em></div>
        </div>
    </div>
    <!-- END FOOTER -->

    <div class="menu bg-danger p-5" id="menu-clickable" style="height:100%;" >
        <ul>
            <?php
            
            ?>
        </ul>
    </div>

</div><!-- #wrapper -->



<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
  Launch demo modal
</button>



<!-- BOOTSTRAP AND JQUERY PLUGINS -->
<script type="text/javascript" src="<?php echo Utils::getAssetURL('bootstrap/js/bootstrap.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/libs/lodash.compat.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/marquee/jquery.marquee.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/libs/jquery.easing.1.3.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/libs/breakpoints.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/respond/respond.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/cycle2/jquery.cycle2.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/onscreenkeyboard/jsKeyboard.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/blockui/jquery.blockUI.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/validation/jquery.validate.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/noty/jquery.noty.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/noty/layouts/top.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/plugins/noty/themes/default.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/app.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/plugins.form-components.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/plugins.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/custom/form_validation.js');?>"></script>
<!-- END UI AND JQUERY PLUGINS -->

<!-- UI PLUGINS INITIALIZATION -->
<script>
$(document).ready(function() {
    App.init();
    Plugins.init();
});
</script>
<!-- END UI PLUGINS INITIALIZATION -->

<!-- REEL FRONT END INITIALIZATION -->
<script>
<?php echo $reel->renderSounds(); ?>
<?php echo $reel->renderOptions(); ?>
</script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/reel.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getAssetURL('assets/js/customslide.js');?>"></script>
<!-- END REEL FRONTEND INITIALIZATION -->
</body>
</html>
<!-- Modal -->
<div id="simpleModal" class="myModal">
    <div class="modal-elements">
        <div class="row justify-content-center">
            <ul>
        <?php 
            $slidesArray = getContents2();
                
            foreach($slidesArray as $item){
                $title = $item['title'];
                $id = $item['url'];
            ?>
            <div  class="col-md-3 click-able-slide" data-id="<?php echo $id ?>" >
                <li>
                    <?php echo $title ?>
                </li>
            </div>
        <?php
        }
        ?>
        </ul>
        </div>

        <div class="fidelity-bank-logo">
            <img src="assets/img/coy-logo.png" />
        </div>

    </div>
</div>