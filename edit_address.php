<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';
require_once __DIR__.'/MediaUtils.php';

$error = null;
$row = array();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Check to see if we have a file to be uploaded
  try{

    $name = $_POST['client_name'];
    $id = $_POST['fid'];
    $address = $_POST['client_address'];

    if(!$id || !strlen($id)){
      throw new Exception('Editing a client address requires its id');
    }

    if(!$name || !strlen($name)){
      throw new Exception('Editing a client address requires a name');
    }

    if(!$address || !strlen($address)){
      throw new Exception('Editing a client address requires its IP address');
    }

    // has this address been added before now
    $name = trim($name);
    $address = trim($address);

    $hash = sha1(trim($address));
    // if we get this far we need to store the records
    $q =
    sprintf('UPDATE resource_media_ipaddress '.
    'SET clientName = "%s", ipAddress="%s", ipAddressHash="%s" '.
    'WHERE id=%s LIMIT 1', $name, $address, $hash, $id);

    executeQuery($q);
    // if we are done we need to redirect to the recource lists
    $uri = 'client_address_lists.php';
    header("Location:{$uri}");
    exit;
  }catch(Exception $ex){
    $error = $ex->getMessage();
  }
}else{
  $did = isset($_GET['id'])?(int)$_GET['id']:0;
  if(!$did || !strlen($did)){
    $uri = 'client_address_lists.php';
    header("Location:{$uri}");
    exit;
  }

  $data =
  queryData(
    'SELECT * FROM resource_media_ipaddress WHERE id ='.$did.' LIMIT 1');

  if(!$data){
    $uri = 'client_address_lists.php';
    header("Location:{$uri}");
    exit;
  }
  $row = $data[0];
}
// pull resources from the
$title = 'Edit Client IP Address';
include_once 'header.php';
?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:60%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Edit Client IP</h2>
              </td>
              <td align="right">
                <a href="resources.php"><b>View Panel</b></a>
              </td>
            </tr>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <form method="post" action="edit_address.php">
                  <input type="hidden" name="fid" id="fid" value="<?php echo $row['id'];?>"/>
                  <table cellpadding="10"
                         cellspacing="10"
                         style="margin-left:auto;margin-right:auto;width:50%;" border="0">
                    <?php if($error !== null):?>
                      <tr>
                        <td align="center">
                          <div class="alert alert-danger"><?php echo $error;?></div>
                        </td>
                      </tr>
                    <?php endif;?>
                    <tr>
                      <td align="center">
                        <span>Client Name</span>
                        <input type="text"
                               id="client_name"
                               name="client_name"
                               value="<?php echo $row['clientName'];?>"
                               autocomplete="off"
                               maxlength="64"/>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <span>Client IP</span>
                        <input type="text"
                               id="client_address"
                               name="client_address"
                               value="<?php echo $row['ipAddress'];?>"
                               autocomplete="off"
                               maxlength="64"/>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <button name="ipadd"
                                class="btn btn-primary">Save Changes</button>
                                &nbsp;&nbsp;
                        <a class="btn btn-default" href="client_address_lists.php">Cancel</a>
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
