<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';
$conn = _connect();

$error = null;
$success = null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // We are dealing with a post request
  try{

    $fhash = $_POST['fhash'];
    $forder = $_POST['forder'];
    $timer = $_POST['timer'] ;
    $fname = $_POST['fname'];
    $address = $_POST['iphash'];
    $id = $_POST['cid'];
    $target_ip_address = $_POST['ip'];
   
    
    if(!$id || !strlen($id)){
      throw new Exception(
        'IP address id not not defined');
    }

    if(!$target_ip_address || !strlen($target_ip_address)){
      throw new Exception(
        'IP address is not defined');
    }

    if(!$fhash || !strlen($fhash)){
      throw new Exception(
        'You have not selected a media resource to schedule');
    }

    if(!$fname || !strlen($fname)){
      throw new Exception(
        'Please specify a name for this schedule');
    }

    if(!$forder || !strlen($forder)){
      throw new Exception(
        'You have not selected the media resource slide position');
    }

    if(!$address || !strlen($address)){
      throw new Exception('Client ip address is not defined');
    }

    // Update the resource_media_files table with some slides information

    // First query the resource_media_files table 
  $q =
  sprintf(
    'SELECT associatedSchedules FROM resource_media_files WHERE fileHash = "%s" LIMIT 1', $fhash);
    $fileOrderArray = queryData2($q);
    
    
    array_push($fileOrderArray,$forder);
    
    $orderString=  implode(',',$fileOrderArray); 
    $orderString = trim($orderString,',');
    $q2 = sprintf('UPDATE resource_media_files SET associatedSchedules = "%s" WHERE fileHash = "%s"'
    , $orderString, $fhash);
    executeQuery($q2);

    // if we get this far we have to store the schedule
    $q = sprintf('INSERT INTO resource_media_schedule '.
    '(fileHash, fileOrder, ipAddressHash, scheduleName,timer) '.
    'VALUES("%s", "%s", "%s", "%s", "%s")'
    , $fhash
    , $forder
    , $address
    , $fname
    ,$timer);
    
  executeQuery($q);

    // rebuild the schedules for display
    try{
      BuildUtil::rebuildSchedules($target_ip_address, $address);
    }catch(Exception $ex){
      // ignore this
      var_dump($ex);exit;
    }
    // we are good so we can lists
    $uri = 'list_resource_schedule.php?cid='.$id;
    header("Location:{$uri}");
    exit;
  }catch(Exception $ex){
    $error = $ex->getMessage();
  }

}

$cid = isset($_GET['cid'])?(int)$_GET['cid']:0;

if(!$cid || !strlen($cid)){
  header("Location:client_address_lists.php");
  exit;
}

$data =
queryData('SELECT * FROM resource_media_ipaddress WHERE id ='.$cid.' LIMIT 1');

// print_r($data);exit;

$schedules = array();

if($data){
  $data = $data[0];
  $q =
  sprintf(
    'SELECT * FROM resource_media_schedule WHERE ipAddressHash = "%s"', $data['ipAddressHash']);
  $schedules = queryData($q);
}


$associatedFile = 

$resources = queryData('SELECT * FROM resource_media_files');
if($resources){
  $resources = ipull($resources, null, 'fileHash', 'id');
}


$scheduled_hashes = array();
$scheduled_orders = array();
$scheduled_orders[] = 22;
$scheduled_orders[] = 23;
$scheduled_orders[] = 24;
$scheduled_orders[] = 25;
$scheduled_orders[] = 26;
$scheduled_orders[] = 28;
$scheduled_orders[] = 31;
$scheduled_orders[] = 34;

if($schedules){
  // group by the fileorder
  $scheduled_hashes = array_values(ipull($schedules, 'fileHash', 'id'));
  $orders = array_values(ipull($schedules, 'fileOrder', 'id'));
  // add the filters we already have pre-defined
  if($scheduled_hashes){
    $scheduled_hashes = array_fill_keys($scheduled_hashes, 1);
  }

  if($orders){
    $scheduled_orders = array_merge($scheduled_orders,$orders);
    $scheduled_orders = array_map('to_int', $scheduled_orders);
  }

  $schedules = isort($schedules, 'fileOrder');
}

$scheduled_orders = array_fill_keys($scheduled_orders, 1);
// pull resources from the
$title = 'Resource Schedule Lists for '.$data['clientName'];
include_once 'header.php';

if(empty($_SESSION['admin_id']) && empty($_SESSION['supper_admin_id'])){

  header('Location: ./admin/index.php');
}

?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:60%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">
                  Schedule Lists for <?php echo $data['clientName'];?></h2>
              </td>
              <td align="right">
                <a href="resource_upload.php"><b>Upload Resource</b></a> &nbsp;&nbsp; - &nbsp;&nbsp;
                <a href="resources.php"><b>View Panel</b></a>
              </td>
            </tr>
            <?php if($error && strlen($error)):?>
              <tr>
                <td colspan="2">
                  <div class="alert alert-danger"><?php echo $error;?></div>
                </td>
              </tr>
            <?php endif;?>
            <?php if($success && strlen($success)):?>
              <tr>
                <td colspan="2">
                  <div class="alert alert-success"><?php echo $success;?></div>
                </td>
              </tr>
            <?php endif;?>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <?php if(count($schedules)):?>
                  <table width="100%">
                    <tbody>
                      <thead style="background-color:#222;color:#fff;font-size:13px;">
                        <th>#</th>
                        <th>Schedule Name</th>
                        <th>File Name</th>
                        <th>Type</th>
                        <th>Slide</th>
                        <th>Time</th>
                        <th></th>
                      </thead>
                      <?php $ctx =1; foreach($schedules as $row):?>
                        <?php $entry = idx($resources, $row['fileHash'], array());?>
                        <tr  <?php $cssclass = (($ctx % 2) === 0)?'odd-row':'even-row';?> class="<?php echo $cssclass;?>" valign="top">
                          <td><?php echo $ctx;?></td>
                          <td><?php echo $row['scheduleName'];?></td>
                          <td><?php echo $entry['fileName'];?></td>
                          <td><?php echo $entry['fileType'];?></td>
                          <td>Slide <?php echo $row['fileOrder'];?></td>
                          <td>Time <?php echo $row['timer'] /1000;?> Seconds</td>
                          <td align="right">
                            <a href="delete_schedule.php?cid=<?php echo $cid;?>&id=<?php echo $row['id'];?>">Delete</a></td>
                        </tr>
                      <?php $ctx++; endforeach;?>
                    </tbody>
                  </table>
                <?php else:?>
                  <span style="text-align:center;color:maroon;display:block;">There are no media resources scheduled</span>
                <?php endif;?>
                <div style="clear:both;?>"></div>
                <hr/>
                <h4>Add Schedule</h4>
                <br/>
                <form style="background:#f6f7f5;"
                      action="list_resource_schedule.php?cid=<?php echo $cid;?>"
                      method="post">
                  <input type="hidden"
                         id="iphash"
                         name="iphash"
                         value="<?php echo $data['ipAddressHash'];?>"/>
                  <input type="hidden"
                         id="ip"
                         name="ip"
                         value="<?php echo $data['ipAddress'];?>"/>
                  
                  <input type="hidden"
                         id="cid"
                         name="cid"
                         value="<?php echo $data['id'];?>"/>
                  <table cellspacing="10" cellpadding="10" style="width:100%;">
                    <tbody>
                      <tr valign="top">
                        <td>
                          <select id="fhash" name="fhash">
                            <option value="">Select Resource</option>
                            <?php foreach($resources as $rs):?>
                              <?php if(!array_key_exists($rs['fileHash'], $scheduled_hashes)):?>
                                <option value="<?php echo $rs['fileHash'];?>"><?php echo $rs['fileName'];?></option>
                              <?php endif;?>
                              <?php endforeach;?>
                            </select>
                          <input type="hidden" value="<?php echo $rs['id'] ?>" name="media_file_id">

                      </td>
                      <td>
                        <input type="text"
                               name="fname"
                               placeholder="Schedule Name ..."
                               maxlength="128" id="fname" value=""/>
                      </td>
                        <td>
                          <select id="forder" name="forder">
                            <option value="">Select Order</option>
                            <?php for($i=22;$i<50;$i++):?>
                              <?php if(!array_key_exists($i, $scheduled_orders)):?>
                                <option value="<?php echo $i;?>">Slide <?php echo $i;?></option>
                              <?php endif;?>
                            <?php endfor;?>
                        </select>
                        </td>

                        <td>
                          <select id="timer" name="timer" required>
                            <option value="">Select time</option>
                            <?php for($i=5;$i<=360;$i=$i+5):?>
                              
                                <option value="<?php echo $i *1000 ;?>"> <?php echo $i;?> Seconds</option>
                              
                            <?php endfor;?>
                        </select>
                        </td>
                        <td align="right">
                          <button class="btn btn-primary">Add New Schedule</button></td>
                      </tr>
                    </tbody>
                  </table>
                </form>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
