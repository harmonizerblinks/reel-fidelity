<?php
require_once 'ExcelParser.php';
$error= null;
$message= null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // we have a post request
  $raw = $_POST['copy_content'];
  if(!$raw || !strlen($raw)){
    $error = 'Please copy and paste the content of the provided excelsheet template';
  }else{
    // we try to process the rates
    try{
      ExcelParser::parseContent($raw);
      $message = 'updates saved successfully';
    }catch(Exception $ex){
      $error = $ex->getMessage();
    }
  }
}
?>
<html>
<head>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css"/>
<style type="text/css">
.areaplace{
  display:block;
  width:670px;
  height:280px;
  background-color:#EEE8AA;
  border:1px solid #f8f8f8;
}
</style>
</head>
<div class="container">
          <table cellpadding="10" cellspacing="10" border="0" class="table">
            <tbody>
              <tr>
                <td align="center" colspan="3">
                  <!-- main content -->
                  <div class="box-content">
                    <h2 class="page-header">Update From Excel</h2>
                  <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST">
                    <table style="width:680px;">
                      <tr>
                        <td style="width:680px;padding:10px;">
                          <?php if($error !== null):?>
                            <span style="display:block;width:100%;" class="alert alert-danger"><?php echo $error;?></span>
                          <?php else:?>
                          <?php if($message !== null):?>
                            <span style="display:block;width:100%;" class="alert alert-success"><?php echo $message;?></span>
                          <?php endif;?>
                        <?php endif;?>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <textarea placeholder="Copy and paste te content of the excel template here..."
                                   class="areaplace"
                                   name="copy_content"></textarea>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div class="alert alert-warning">
                            After modifying the content of the provided Excel
                            template, copy the modified content, paste it on the above box
                            and then click on Save Changes to persist it</div></td>
                      </tr>
                      <tr>
                        <td align="right">
                          <input name="__postcopy__" type="submit" value="Save Changes"/>
                          <a style="padding:10px;" href="updates.php">Cancel Update</a>
                        </td>

                      </tr>
                    </table>
                  </form>
                </div>
                <!-- main content ends -->
              </tr>
            </tbody>
          </table>
        </div>
    </body>
</html>
