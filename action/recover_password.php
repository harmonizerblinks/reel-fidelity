<?php
require_once '../connection.php';
session_start();
$url = '../super_admin/forget_password.php';
$url2 = '../super_admin/index.php';
if(isset($_POST['recover_password'])){
$error='';
$email = $_POST['email'];
$newPassword = $_POST['password'];
$confirmNewPassword = $_POST['confirm_password'];
$q1 =   sprintf('SELECT * FROM supper_admin WHERE email = "%s"', $email);
$passwordHash = password_hash($newPassword,PASSWORD_DEFAULT);

$adminRecord  = queryData3($q1);

if(!$adminRecord){
  $error = "No User with this email is found in the system";
}else if(strlen($newPassword)  < 7){
$error =  "New password must be greater then 6 charaters";
}
else if( strcmp($newPassword,$confirmNewPassword) !== 0){
  $error = " New Password and Confirm Password must match";
}
elseif(!preg_match("#[0-9]+#",$newPassword)) {
  $error = "Your Password Must Contain At Least a Numeric value!";
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
    $q =  sprintf('UPDATE  supper_admin SET password="%s" WHERE email = "%s"',$passwordHash, $email);
    executeQuery($q);
    header("Location:{$url2}");
  }
}
?>