<?php
include_once "../connection.php";
session_start();
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){

  // Create admin user
  if(isset($_POST['admin'])){
    
  $uri = '../admin/register.php';
  $uri2 = '../super_admin/dashboard.php';
  try{

    $name = $_POST['name'];
    $email= $_POST['email'];
    $password = $_POST['password']; 
    $confirmNewPassword = $_POST['confirm_password'];
    if(!$name || !strlen($name)){
      $error = 'Name field is required';
    }

    if(!$email || !strlen($email)){
      $error = 'Email is required';
    }

    if(!$password || !strlen($password)){
      $error = 'Password is required';
    }else if(strlen($password)  < 7){
      $error =  "New password must be greater then 6 charaters";
    }
  
    elseif(!preg_match("#[0-9]+#",$password)) {
      $error = "Your Password Must Contain At Least 1 Number!";
    }
    elseif(!preg_match("#[A-Z]+#",$password)) {
      $error = "Your Password Must Contain At Least 1 Capital Letter!";
    }
    elseif(!preg_match("#[a-z]+#",$password)) {
      $error = "Your Password Must Contain At Least 1 Lowercase Letter!";
    }else if( strcmp($password,$confirmNewPassword) !== 0){
      $error = "Password and Confirm Password must match";
    }

    // has this address been added before now
    $name = trim($name);
    $email = trim($email);

    $passwordHash = password_hash($password,PASSWORD_DEFAULT);
    $q1 =
    sprintf(
      'SELECT * FROM admin WHERE email = "%s"'
      , $email);

    $email2 = queryData($q1);
    if($email2 !=null){
      
       $error= 'User  with '.$email.' already exist in the system';
    }

    if(!empty($error)){
      $_SESSION['error'] = $error;
      header("Location:{$uri}");
    }
    // if we get this far we need to store the records
    else{
      
    $q =
    sprintf(
      'INSERT INTO admin '.
      '(admin_name,email,password,raw_password) '.
      'VALUES("%s", "%s", "%s","%s")'
      , $name
      , $email
      , $passwordHash,$password);

    executeQuery($q);
    // if we are done we need to redirect to the recource lists
    $_SESSION['success'] = "Admin registered sucessfully";
    header("Location:{$uri2}");
    exit;
    }
  }catch(Exception $ex){
    $error = $ex->getMessage();
  }

}
// Admin creation end here

  // Create the supper admin user

  if(isset($_POST['supper_admin'])){
    $uri = '../super_admin/index.php';
    $uri2 = '../super_admin/register.php';
    try{
      $name = $_POST['name'];
      $email= $_POST['email'];
      $password = $_POST['password']; 
      $confirmNewPassword = $_POST['confirm_password'];
      if(!$name || !strlen($name)){
        $error = 'Name field is required';
      }
  
      if(!$email || !strlen($email)){
        $error = 'Email is required';
      }
  
      if(!$password || !strlen($password)){
        $error = 'Password is required';
      }else if(strlen($password)  < 7){
        $error =  "Password must be greater then 6 charaters";
      }else if( strcmp($password,$confirmNewPassword) !== 0){
        $error = " New Password and Confirm Password must match";
      }
    
      elseif(!preg_match("#[0-9]+#",$password)) {
        $error = "Your Password Must Contain At Least 1 Number!";
      }
      elseif(!preg_match("#[A-Z]+#",$password)) {
        $error = "Your Password Must Contain At Least 1 Capital Letter!";
      }
      elseif(!preg_match("#[a-z]+#",$password)) {
        $error = "Your Password Must Contain At Least 1 Lowercase Letter!";
      }
  
      // has this address been added before now
      $name = trim($name);
      $email = trim($email);

      $q=
    sprintf(
      'SELECT * FROM supper_admin WHERE email = "%s"'
      , $email);

    $email2 = queryData($q);
    if($email2 !=null){
      
       $error= 'User  with '.$email.' already exist in the system';
    }
  
      $passwordHash = password_hash($password,PASSWORD_DEFAULT);
      $q1 =
      sprintf(
        'SELECT * FROM supper_admin'
        , $email);
  
      $adminRecord = queryData3($q1);
      if(count($adminRecord) == 4){
        
         $error= 'The maximum number of super admin users allowed have been reached';
      }
  
      if(!empty($error)){
        $_SESSION['error'] = $error;
        header("Location:{$uri2}");
      }
      // if we get this far we need to store the records
      else{
        
      $q =
      sprintf(
        'INSERT INTO supper_admin '.
        '(supper_admin_name,email,password) '.
        'VALUES("%s", "%s", "%s")'
        , $name
        , $email
        , $passwordHash);
  
      executeQuery($q);
      // if we are done we need to redirect to the recource lists
      $_SESSION['success'] = "Admin registered sucessfully";
      header("Location:{$uri}");
      exit;
      }
    }catch(Exception $ex){
      $error = $ex->getMessage();
    }
  }
}