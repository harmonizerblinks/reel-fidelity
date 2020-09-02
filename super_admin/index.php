<?php
session_start();
include_once '../header3.php';
?>
<section style="background:#ddd8d8; padding:20px; position:relative;top:50px;">
<!-- require the message file to display any flash message -->

<?php require_once '../helpers/messages.php'; ?>
  <div class="row justify-content-center " >
    <div class="col-md-3"></div>
    <div class="col-md-6 shadow-lg">
      <h3>Super Admin Login</h3>
      <form action="../action/login.php" method="POST">
        <div class="form-group">
          <label for="email">Email</label>
          <input type="text" name="email" autocomplete= "off" autofocus placeholder="enter your email" class="form-control">
        </div>
        <div class="form-group">
          <label for="password">password</label>
          <input type="password" name="password" placeholder="enter password here" class="form-control">
        </div>
        <div class="form-group">
          <button class="btn btn-default text-info" name="supper_admin_login">
            Go To Media Page
          </button>
          <a href="./forget_password.php">Forget Password?</a>
        </div>
      </form>
    </div>
    <div class="col-md-3"></div>
  </div>
</section>

