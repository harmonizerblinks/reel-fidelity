<div class="text-danger text-center">
  <?php if(isset($_SESSION['error'])){ ?>
   <?php echo $_SESSION['error'];
   unset($_SESSION['error'])
   ?>
  <?php } ?>
</div>
<div class="text-center text-success ">
  <?php if(isset($_SESSION['success'])){ ?>
   <?php echo $_SESSION['success'];
   unset($_SESSION['success'])
   ?>
  <?php } ?>
</div>