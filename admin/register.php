<?php
require_once  '../connection.php';
session_start();
require_once '../helpers/helpersfunctions.php';
if(!supper_admin_is_logged_in()){
  $_SESSION['error'] = 'Only logged in supper admin can access that page';
  header('Location:../supper_admin/index.php');
}
if(isset($_GET['id'])){
  $id = strip_tags($_GET['id']);
  $action = "../action/update.php";
}else{ 
  $id = '';
 $action =  "../action/register.php";
}

if($id !=''){
  $q1 =
  sprintf(
    'SELECT * FROM admin WHERE admin_id = "%s"'
    , $id);

    $adminToBeEdited =  queryData4($q1);  
}else{
  $adminToBeEdited = '';
}
include_once '../header3.php';

?>

<section style="background:#ddd8d8; padding:20px; position:relative;top:50px;">

   <!-- require the meaasge file here to display flash messages -->
   <?php require_once '../helpers/messages.php'; ?>
  <div class="row justify-content-center " >
    <div class="col-md-3"></div>
    <div class="col-md-6 shadow-lg">
      <h3>Admin Register</h3>
      <form action="<?php echo $action?>" method="POST" class="shadow-lg bg-white p-5">
      <div class="form-group">
        <label for="name">User Name</label>
        <input type="text" required="required" name="name" value="<?php if(!empty($id)) {echo $adminToBeEdited['admin_name'];}?>" class="form-control">
      </div>
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" required="required" name="email" class="form-control" value="<?php if(!empty($id)) {echo $adminToBeEdited['email'];}?>">
      </div>
      <div class="form-group">
        <label for="password">password</label>
        <input type="password" required = "required" name="password" value="<?php if(!empty($id)) {echo $adminToBeEdited['raw_pasword'];}?>" class="form-control">
        <small class="text-danger">
          Password must be greater than or equall to 8 characters and must contain a number and an upper case charater
        </small>
      </div>
      <div class="form-group">
        <label for="confirm_password"> Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="enter confirm password here" class="form-control">
      </div>
      <div class="form-group">
        <button class="btn btn-info btn-md"  name="admin">
          <?php if($id !=''){echo 'Update';}else{echo " Register";} ?> User
      </button>
      <a href="../super_admin/dashboard.php" class="btn btn-sm btn-warning">Go To Dashboard</a>
      </div>
      <input type="hidden" value="<?php if(!empty($id)) {echo $adminToBeEdited['admin_id'];} ?>" name="id">
    </form>
    </div>
    <div class="col-md-3"></div>
  </div>
</section>

