<?php
session_start();
include_once '../header3.php';

?>
<section style="background:#ddd8d8; padding:20px; position:relative;top:50px;">
   <!-- require the meaasge file here to display flash messages -->
  <?php require_once '../helpers/messages.php'; ?>

  <div class="row justify-content-center " >
    <div class="col-md-3"></div>
    <div class="col-md-6 shadow-lg">
      <h3>Recover Password</h3>
      <form action="../action/recover_password.php" method="POST" class="shadow-lg bg-white p-5">
      <div class="form-group">
        <label for="email">Email</label>
        <input type="text" required="required" placeholder="enter the email you registered with to recover your password" name="email" class="form-control">
      </div>
      <div class="form-group">
          <label for="password"> Password</label>
          <input type="password" name="password" placeholder="enter password here" class="form-control">
          <small class="text-danger">
            Password must contain one Uppercase charater and one lowercase charater and a number
          </small>
        </div>
        <div class="form-group">
          <label for="confirm_password"> Confirm Password</label>
          <input type="password" name="confirm_password" placeholder="enter confirm password here" class="form-control">
        </div>
      <div class="form-group">
        <button class="btn btn-info btn-md"  name="recover_password">
        Recover Password
      </button>
      <a href="index.php" class="btn btn-primary">Go Back</a>
      </div>
    </form>
    </div>
    <div class="col-md-3"></div>
  </div>
</section>

