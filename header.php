<?php 

if(isset($_POST['timer-button'])){
$timer = $_POST['timer'];
header("Location: ./resources.php");
$_SESSION['time'] = $timer;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1">
<title><?php echo isset($title)?$title:null;?></title>
<link rel="icon" href="/assets/img/coy-logo.png" type="image/png">
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
background:#f8f6f4;
border:1px solid #d8d8d8;
}

.page-header{
display:block;
margin-left:0px;
margin-right:0px;
width:100%;
padding:10px;
border-bottom: 3px solid #f89417;
margin-bottom:10px;
}

.odd-row{
  background-color: #dae4f0;
  border-top:1px solid #d9d9d9;
}
.even-row{
  background-color: #fff;
  border-top:1px solid #d9d9d9;
}

select{
  display:block;
  width:210px;
  border:1px solid #d8d7d8;
  background: lightgreen;
  font-size:13px;
  padding:5px;
}
input{
  display:block;
  width:280px;
  border:1px solid #d8d7d8;
  background: lightgreen;
  font-size:13px;
  padding:5px;
}
 </style>
</head>
<body style="background-color:#fff;" class="homesss">
<div class="page-header">
  <div style="clear:both;"></div>
  <div class="pull-left">
    <img src="assets/img/coy-logo.png" title="Fidelity"/>
    
    <?php
    require_once './helpers/helpersfunctions.php';
    if(is_logged_in()) {?>
    <div style="position:absolute;right:10px; top:60px;">
        <a href="admin/change_password.php" class="text-info">Change Password</a> &nbsp;   &nbsp; 
        <?php }
        if(supper_admin_is_logged_in()) {?>
          <div style="position:absolute;right:10px; top:60px;">
              <a href="super_admin/dashboard.php" class="text-info">Back To Dashboard</a> &nbsp;   &nbsp; 
              <?php } 
             if(!isset($_SESSION['supper_admin_id']) AND !is_logged_in()){

              header('Location: ./admin/index.php');
            }
        ?>
        
        <a href="logout.php" class="text-danger" >Logout</a >

    </div>
  </div>
  <div class="pull-right"></div>
  <div style="clear:both;"></div>
  
</div>
<div class="row">
  <div class="col-md-4"></div>
  <div class="col-md-8">
    <form action="" method="POST">
      <div class="row">
        <div class="col-md-4">
          <select name="timer" id="" required>
            <option value="">Select slides duration</option>
            <?php 
            for ($i=5; $i<=360;$i=$i+5){ ?>
              <option value="<?php echo $i * 1000 ?>"><?php echo $i. " Seconds" ?></option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-4">
          <button class="btn btn-warning"  name="timer-button" >Set slides duration</button>
        </div>
        <?php if(isset($_SESSION['time'])){ ?>
        <div class="col-md-4">
          <span class="btn" style="background:olive; color:#f8f6f4">
           <?php echo "Rates  duration ". $_SESSION['time'] / 1000 ;?> Seconds
          </span>
        </div>
        <?php } ?>
      </div>
    </form>
  </div>
</div>


