<?php
session_start();

include_once '../header3.php';

?>
<section style="background:#ddd8d8; padding:20px; position:relative;top:50px;">
<?php require_once '../helpers/messages.php'; ?>
  <div class="row justify-content-center " >
    <div class="col-md-3"></div>
    <div class="col-md-6 shadow-lg">
      <h3>Change Password</h3>
      <form action="../action/change_password.php" method="POST">
      <div class="form-group">
          <label for="oldpassword">Old Password</label>
          <input type="password" name="oldpassword" autocomplete= "off" autofocus placeholder="enter your old password here" class="form-control">
        </div>
        <div class="form-group">
          <label for="password"> Password</label>
          <input type="password" name="password" placeholder="enter password here" class="form-control">
        </div>
        <div class="form-group">
          <label for="confirm_password"> Confirm Password</label>
          <input type="password" name="confirm_password" placeholder="enter confirm password here" class="form-control">
        </div>
        <div class="form-group">
          <button class="btn btn-danger" name="supper_admin_change_password">
            Change Password
          </button>
          <a class="btn btn-primary" href="./dashboard.php">
           Cancel
          </a>
        </div>
        <input type="hidden" name="id" value="<?php echo $_SESSION['supper_admin_id']?>">
      </form>
    </div>
    <div class="col-md-3"></div>
  </div>
</section>

