<?php
include_once "../connection.php";
session_start();
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Update admin user
  if(isset($_POST['admin'])){
    
  $uri = '../admin/register.php';
  $uri2 = '../super_admin/dashboard.php';
  try{

    $name = $_POST['name'];
    $email= $_POST['email'];
    $password = $_POST['password']; 
    $id = $_POST['id'];
    if(!$name || !strlen($name)){
      $error = 'Name field is required';
    }

    if(!$email || !strlen($email)){
      $error = 'Email is required';
    }

    if(!$password || !strlen($password)){
      $error = 'Password is required';
    }

    // has this address been added before now
    $name = trim($name);
    $email = trim($email);

    $passwordHash = password_hash($password,PASSWORD_DEFAULT);
    $q1 =
    sprintf(
      'SELECT * FROM admin WHERE admin_id = "%s"'
      , $id);

    $adminRecord  = queryData4($q1);
    $adminEmail = $adminRecord['email'];

    $q12 =
    sprintf(
      'SELECT email FROM admin WHERE email  != "%s"'
      , $email);

    $adminRecords = queryData3($q12);
    $emails = array_column($adminRecords,'email');
    if(in_array($email, $emails)){
      $error= 'User  with '.$email.' already exist in the system';
    }
    if($email2 !=null){
      
       $error= 'User  with '.$email.' already exist in the system';
    }

    if(!empty($error)){
      $_SESSION['error'] = $error;
      header("Location:{$uri}");
    }
    // if we get this far we need to store the records
    else{
      
    $q =  sprintf('UPDATE  admin SET admin_name="%s",email="%s", password="%s",raw_password ="%s" WHERE admin_id = "%s"', $name , $email , $passwordHash,$password, $id);
    executeQuery($q);
    // if we are done we need to redirect to the recource lists
    $_SESSION['success'] = "Admin Update sucessfully";
    header("Location:{$uri2}");
    exit;
    }
  }catch(Exception $ex){
    $error = $ex->getMessage();
  }

}
}