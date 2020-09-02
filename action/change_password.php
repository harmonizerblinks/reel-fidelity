<?php
require_once '../connection.php';
session_start();
$url = '../admin/change_password.php';
$url2 = '../resources.php';
if(isset($_POST['change_password'])){
  $error='';
$id = $_POST['id'];
$oldPassword = $_POST['oldpassword'];
$newPassword = $_POST['new_password'];
$confirmNewPassword = $_POST['confirm_password'];
$q1 =
    sprintf(
      'SELECT * FROM admin WHERE admin_id = "%s"'
      , $id);

    $adminRecord  = queryData4($q1);
    $databasePassword = $adminRecord['password'];
if(!password_verify($oldPassword,$databasePassword)){
  $error = "Your Old Password does not correspond with our record";
}else if(empty($oldPassword) || empty($newPassword)){
  $error =  "Both old and new password fields are required";
}else if(strlen($newPassword)  < 7){
  $error =  "New password must be greater then 6 charaters";
}
else if( strcmp($newPassword,$confirmNewPassword) !== 0){
  $error = " New Password and Confirm Password must match";
}
elseif(!preg_match("#[0-9]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least 1 Number!";
}
elseif(!preg_match("#[A-Z]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least 1 Capital Letter!";
}
elseif(!preg_match("#[a-z]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least 1 Lowercase Letter!";
}

  if(!empty($error)){
    $_SESSION['error'] = $error;
    header("Location: {$url}");
  }else{
    $passwordHash = password_hash($newPassword,PASSWORD_DEFAULT);
    $q =
    sprintf(
      'UPDATE  admin SET password="%s",raw_password ="%s" WHERE admin_id = "%s"',$passwordHash,$newPassword, $id);
    executeQuery($q);
    // if we are done we need to redirect to the recource lists
    $_SESSION['success'] = "Password  Update sucessfully";
    header("Location:{$url2}");
  }
}


if(isset($_POST['supper_admin_change_password'])){
  $url = '../super_admin/change_password.php';
  $url2 = '../super_admin/dashboard.php';
  $error='';
  $id = $_POST['id'];
$oldPassword = $_POST['oldpassword'];
$newPassword = $_POST['password'];
$confirmNewPassword = $_POST['confirm_password'];
$q1 =
    sprintf(
      'SELECT * FROM supper_admin WHERE supper_admin_id = "%s"'
      , $id);

    $adminRecord  = queryData4($q1);
    $databasePassword = $adminRecord['password'];
if(!password_verify($oldPassword,$databasePassword)){
  $error = "Your Old Password does not correspond with our record";
}else if(empty($oldPassword) || empty($newPassword)){
  $error =  "Both old and new password fields are required";
}else if(strlen($newPassword)  < 7){
  $error =  "New password must be greater then 6 charaters";
}
else if( strcmp($newPassword,$confirmNewPassword) !== 0){
  $error = " New Password and Confirm Password must match";
}
elseif(!preg_match("#[0-9]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least 1 Number!";
}
elseif(!preg_match("#[A-Z]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least 1 Capital Letter!";
}
elseif(!preg_match("#[a-z]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least 1 Lowercase Letter!";
}

  if(!empty($error)){
    $_SESSION['error'] = $error;
    header("Location: {$url}");
  }else{
    $passwordHash = password_hash($newPassword,PASSWORD_DEFAULT);
    $q =
    sprintf(
      'UPDATE  supper_admin SET password="%s" WHERE supper_admin_id = "%s"',$passwordHash, $id);
    executeQuery($q);
    // if we are done we need to redirect to the recource lists
    $_SESSION['success'] = "Password  Update sucessfully";
    header("Location:{$url2}");
  }
}
?>