<?php
session_start();
if(!empty($_SESSION['admin_id'])){
  unset($_SESSION['admin_id']);
  $_SESSION['success'] = "Logout Successfull";
  header("Location:admin/index.php");
}
if(isset($_SESSION['supper_admin_id'])){
  unset($_SESSION['supper_admin_id']);
  $_SESSION['success'] = "Logout Successfull";
  header("Location:super_admin/index.php");
}
?>