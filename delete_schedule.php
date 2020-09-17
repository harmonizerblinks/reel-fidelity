<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';
// require_once __DIR__.'/MediaUtils.php';
// first we get the id of the doucment we want to delete
$error = null;
$row = array();
$data = array();
$did = isset($_GET['id'])?(int)$_GET['id']:0;
$cid = isset($_GET['cid'])?(int)$_GET['cid']:0;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // we are deleting
  $sid = $_POST['sid'];
  $cid = $_POST['cid'];
  $fileOrder = $_POST['fileOrder'];
  $target_ip_address = $_POST['ip'];
  $fileHash = $_POST['fileHash'];
  $ipAddressHash = $_POST['ipAddressHash'];


  // Get the file that this schedule is associted with and update the associated schedule column in that table

  $sql =
  sprintf(
    'SELECT associatedSchedules FROM resource_media_files WHERE fileHash = "%s" LIMIT 1', $fileHash);
    $associatedSchedulesToBeUpdated = queryData2($sql);
    $associatedSchedulesToBeUpdatedInString =implode(',', $associatedSchedulesToBeUpdated);

    $newStringValue = str_replace($fileOrder,'',$associatedSchedulesToBeUpdatedInString);
    $newStringValue = trim($newStringValue, ',');

    // Update the resource_media_file table with the new value of the assciatedSchedule by removing the schedule

    $q2 = sprintf('UPDATE resource_media_files SET associatedSchedules = "%s" WHERE fileHash = "%s"'
    , $newStringValue, $fileHash);
    executeQuery($q2);

  try{

    if(!$sid || !strlen($sid)){
      throw new Exception(
        'Deleteing a resource schedule requires the resource id');
    }
      // we need to write this to a file
  $root = MediaUtils::getApplicationRoot();
  
  

// Get the slides directory to delete the file while deleting the schedule
$path = $root.DIRECTORY_SEPARATOR.'slides';
  
$files = array_diff(scandir($path), array('.', '..'));
foreach($files as $file){
  $fileToBeRemoved = explode('.', $file);
    $fileNameWithoutExtension = $fileToBeRemoved[0];
    $extension= $fileToBeRemoved[1];
    $fileFolderWithHAtTheFront = "h".$fileOrder;
    if($fileNameWithoutExtension === $fileFolderWithHAtTheFront){
      $fullPathToFile = $path.DIRECTORY_SEPARATOR.$fileNameWithoutExtension.".".$extension;
      // At this point, delete the file as you are deleting the schedule
      unlink($fullPathToFile);
    }
  }



    $q = "DELETE FROM resource_media_schedule WHERE id = {$sid} LIMIT 1";
    executeQuery($q);

    // rebuild the resources
    try{
      BuildUtil::rebuildSchedules($target_ip_address,$ipAddressHash);
    }catch(Exception $ex){
      // ignore this
    }

    // we are done we need to redirect to the list page
    $uri = 'list_resource_schedule.php?cid='.$cid;
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

  if(!$cid || !strlen($cid)){
    header("Location:client_address_lists.php");
    exit;
  }
  // read the data we want to delete

  $data = queryData('SELECT * FROM resource_media_schedule WHERE id ='.$did);
  if(!$data){
    header("Location:client_address_lists.php");
    exit;
  }

  $row = $data[0];
  $ip_query =
  sprintf('SELECT * FROM resource_media_ipaddress '.
  'WHERE ipAddressHash ="%s"', $row['ipAddressHash']);
  $ip_data = queryData($ip_query);

  if($ip_data){
    $ip_data = $ip_data[0];
  }
}

// pull resources from the
$title = 'Delete Resource Schedule';
include_once 'header.php';
?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:60%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Delete Resource Schedule</h2>
              </td>
              <td>
                <a href="resources.php">View Panel</a>
              </td>
            </tr>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <form method="post" action="delete_schedule.php?cid=<?php echo $cid;?>&id=<?php echo $did;?>">
                  <input type="hidden" name="sid" id="sid" value="<?php echo $row['id'];?>"/>
                  <input type="hidden" name="fileOrder" id="fileOrder" value="<?php echo $row['fileOrder'];?>"/>
                  <input type="hidden" name="fileHash" id="fileHash" value="<?php echo $row['fileHash'];?>"/>
                  <input type="hidden" name="cid" id="cid" value="<?php echo $cid;?>"/>
                  <input type="hidden" name="ip" id="ip" value="<?php echo $ip_data['ipAddress'];?>"/>
                  <input type="hidden" name="ipAddressHash" id="ipAddressHash" value="<?php echo $ip_data['ipAddressHash'];?>"/>
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
                          Are you sure you want to delete this
                          scheduled media resource?</strong></div>
                      </td>
                    </tr>
                    <tr valign="top">
                      <td>
                        <div style="clear:both;">
                          <div class="pull-left">
                            <button class="btn btn-primary">Confirm & Delete</button></div>
                          <div class="pull-right">
                            <a class ="btn btn-default"
                               href="list_resource_schedule.php?cid=<?php echo $cid;?>&id=<?php echo $did;?>">Cancel</a>
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
