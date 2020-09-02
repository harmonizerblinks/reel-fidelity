<?php
session_start();
// Just for redirecting to the resource file
require_once '../helpers/helpersfunctions.php';
if(supper_admin_is_logged_in() || is_logged_in()){

  $uri = '../resources.php';
  header("Location:{$uri}");
}else{
  $_SESSION['error'] = "You must be logged in to access that";
  
  header('Location: ../admin/index.php');
}
