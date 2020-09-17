<?php

function is_logged_in(){
  if(isset($_SESSION['admin_id'])){
    return true;
  }else{
    return false;
  }
}

function supper_admin_is_logged_in(){
  if(isset($_SESSION['supper_admin_id'])){
    return true;
  }else{
    return false;
  }
}