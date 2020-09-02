<?php
require_once '../connection.php';
session_start();
require_once '../helpers/helpersfunctions.php';
if(!supper_admin_is_logged_in()){
  $_SESSION['error'] =  "Please login";
  header("Location: ./index.php");
}
include_once '../header3.php';
?>

<hr style="border: 0px solid white;">
<section class="py-3">
<?php require_once '../helpers/messages.php' ?>
  <div class="row">
    <div class="col-md-6" style="background:#fff;">
      <div class="card">
        <div class="card-body">
            <a href="../admin/register.php" class="btn btn-sm btn-info"> Create An Admin User</a>
        </div>
      </div>
    </div>
    <div class="col-md-6" style="background:#fff;">
      <div class="card">
        <div class="card-body ">
          
            <a href="../management/index.php" class="btn btn-sm btn-primary"> Go To Media Page</a>

            <a href="./change_password.php" class="btn btn-sm btn-info"> Change Password</a>
          
            <a href="../logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
      </div>
    </div>
  </div>
</section>
<hr style="border-bottom: 1px solid red;">
<section>
  <h2 class="text-center">List Of Users</h2>
  <table class="table table-striped">
    <thead>
      <th>S/N</th>
      <th>Email</th>
      <th>Name</th>
      <th>Raw Password</th>
      <th>Actions</th>
    </thead>

    <tbody>
      <?php 
      $q1 =   'SELECT * FROM admin';
  
      $users = queryData($q1);
      
      if(!empty($users)) {?>
        <?php foreach ($users as $key => $value) {?>
        <tr>
          <td><?php  echo $key + 1 ?></td>
          <td><?php  echo $value['email'] ?></td>
          <td><?php  echo $value['admin_name'] ?></td>
          <td><?php  echo $value['raw_password'] ?></td>
          <td>
            <a href="../admin/register.php?id=<?php echo $value['admin_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
            <form action="" method="POST" style="display:inline">
              <button  class="btn btn-sm btn-danger" name="delete">Delete</button>
              <input type="hidden" value="<?php echo $value['admin_id']?>" name="admin_id">
            </form>
          </td>
        </tr>
      <?php } } ?>
    </tbody>
  </table>
</section>

<!-- Delete A user -->
<?php 
if(isset($_POST['delete'])){
  $id =strip_tags($_POST['admin_id']);
  $id = trim($id);
  $q = sprintf('DELETE FROM admin WHERE admin_id = "%s" ', $id);
  executeQuery($q);
  header("Location: dashboard.php");
}
?>