<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, maximum-scale=1">
<title><?php echo $title;?></title>
<link rel="icon" href="./img/logo.png" type="image/png">

<link href="<?php echo Utils::getCSSPath('bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="<?php echo Utils::getJSPath('js/fancybox/jquery.fancybox.css');?>" type="text/css" media="screen">
<link href="<?php echo Utils::getCSSPath('font-awesome/css/font-awesome.min.css');?>" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="<?php echo Utils::getCSSPath('css/common.css'); ?>" />

<script type="text/javascript" src="<?php echo Utils::getJSPath('js/jquery-1.11.0.min.js');?>"></script>
<script type="text/javascript" src="<?php echo Utils::getJSPath('bootstrap/js/bootstrap.min.js');?>"></script>

<!--[if lt IE 9]>
    <script src="js/respond-1.1.0.min.js"></script>
    <script src="js/html5shiv.js"></script>
    <script src="js/html5element.js"></script>
<![endif]-->

 <style type="text/css">
 body{
   font-family:Dax;
   font-size:30px;
   background-color:#d9d9d9;
 }
 h1{
	 font-family:Dax;
    font-size:35px;
 }

 h3{
	 font-family:Dax;
    font-size:30px;
 }
 	.th_one{
 		background-color: #ddd;
    background-color: #dbe6f3;
	background-color:#b5d334;
 	}
 	.row_margin {
 		margin-top: 50px;
 	}
 	.table_center tbody tr td, .table_head_center tr th{
 		text-align: center;
 	}
 	.indicator{
 		margin-left: 35px;
 	}
 	.fa-caret-up {
 		color: #00dd00;
 	}
 	.fa-caret-down {
 		color: #dd0000;
 	}
 	.fa-minus {
 		color:#aaa;
 	}
 	.fa-caret-up, .fa-caret-down, .fa-minus{
 		font-size: 26px;
 	}
 	.blue_sub_head{
 		color: #aa00dd;
 	}
 	/*modal style*/
	.ecobank_header{
		background-color: #004771;
		color: #fff;
	}
	@media (min-width: 768px){
		.custom_modal_sm {
	    	width: 450px;
		}
	}
 	/*modal style ends*/
 </style>
</head>
<body>
