<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';
require_once __DIR__.'/MediaUtils.php';
// first we get the id of the doucment we want to delete
$error = null;
$row = array();
$data = array();
$did = isset($_GET['id'])?(int)$_GET['id']:0;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // we are deleting
  $fid = $_POST['fid'];

  try{

    if(!$fid || !strlen($fid)){
      throw new Exception('Deleteing a client address requires the address id');
    }

    $q = "DELETE FROM resource_media_ipaddress WHERE id = {$fid} LIMIT 1";
    executeQuery($q);
    // we are done we need to redirect to the list page
    $uri = 'client_address_lists.php';
    header("Location:{$uri}");
    exit;
  }catch(Exception $ex){
    $error = $ex->getMessage();
  }

}else{

  if(!$did || !strlen($did)){
    header("Location:client_address_lists.php");
    exit;
  }
  // read the data we want to delete

  $data = queryData('SELECT * FROM resource_media_ipaddress WHERE id ='.$did);
  if(!$data){
    header("Location:client_address_lists.php");
    exit;
  }

  $row = $data[0];
}

// pull resources from the
$title = 'Delete Client IP';
include_once 'header.php';
?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:100%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Delete Resource</h2>
              </td>
              <td>
                <a href="resources.php">View Panel</a>
              </td>
            </tr>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <form method="post" action="delete_address.php?id=<?php echo $did;?>">
                  <input type="hidden" name="fid" id="fid" value="<?php echo $row['id'];?>"/>
                  <table style="margin-left:auto;margin-right:auto;width:50%;">
                    <?php if($error !== null):?>
                      <tr>
                        <td align="center">
                          <div class="alert alert-danger"><?php echo $error;?></div>
                        </td>
                      </tr>
                    <?php endif;?>
                    <tr valign="top">
                      <td>
                        <div class="alert alert-warning">
                          Are you sure you want to delete this client IP address
                          <strong><?php echo $row['clientName'];?>. Deleting
                            this ip address will also delete all media already
                            scheduled for this client</strong></div>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td>
                        <div style="clear:both;">
                          <div class="pull-left">
                            <button class="btn btn-primary">Confirm & Delete</button></div>
                          <div class="pull-right">
                            <a class ="btn btn-default"
                               href="client_address_lists.php">Cancel</a>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </table>
                </form>
                <div style="clear:both;?>"></div>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
