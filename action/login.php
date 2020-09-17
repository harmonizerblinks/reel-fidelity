<?php
include_once "../connection.php";
session_start();

  $error = '';


if($_SERVER['REQUEST_METHOD'] === 'POST'){
  if(isset($_POST['admin'])){

    $uri = '../admin/index.php';
    try{
      $password= $_POST['password'];
      $email = trim($_POST['email']); 
      if(!$email || !strlen($email)){
        $error = 'Email is required';
      }
  
      elseif(!$password || !strlen($password)){
        $error = 'Password is required';
      }
  
      // has this address been added before now
      $q1 =
      sprintf(
        'SELECT * FROM admin WHERE email = "%s"'
        , $email);
  
      $adminUser = queryData4($q1);
      if(!$adminUser){
         $error= 'No User with this record';
      }else if(!password_verify($password, $adminUser['password'])){
        $error = "Invalid Credentials, please check your inputs";
        
      }
  
      if(!empty($error)){
        $_SESSION['error'] = $error; 
        
        header("Location:{$uri}");
      }
      // if we get this far we need to store the records
      else{
        
      $_SESSION['admin_id'] = $adminUser['admin_id'];
      // if we are done we need to redirect to the recource lists
      $_SESSION['success'] = "Login Success";
      $uri = "../management/index.php";
      header("Location:{$uri}");
      exit;
      }
    }catch(Exception $ex){
      $error = $ex->getMessage();
    }
  }
  if(isset($_POST['supper_admin_login'])){
    $uri = '../super_admin/index.php';
    try{
      $password= $_POST['password'];
      $email = trim($_POST['email']); 
      if(!$email || !strlen($email)){
        $error = 'Email is required';
      }
  
      elseif(!$password || !strlen($password)){
        $error = 'Password is required';
      }
  
      // has this address been added before now
      $q1 =
      sprintf(
        'SELECT * FROM supper_admin WHERE email = "%s"'
        , $email);
  
      $adminUser = queryData4($q1);
      if(!$adminUser){
         $error= 'No User with this record';
      }else if(!password_verify($password, $adminUser['password'])){
        $error = "Invalid Credentials, please check your inputs";
        
      }
  
      if(!empty($error)){
        $_SESSION['error'] = $error; 
        
        header("Location:{$uri}");
      }
      // if we get this far we need to store the records
      else{
        
      $_SESSION['supper_admin_id'] = $adminUser['supper_admin_id'];
      // if we are done we need to redirect to the recource lists
      $_SESSION['success'] = "Login Success";
      $uri = "../super_admin/dashboard.php";
      header("Location:{$uri}");
      exit;
      }
    }catch(Exception $ex){
      $error = $ex->getMessage();
    }
  }
}