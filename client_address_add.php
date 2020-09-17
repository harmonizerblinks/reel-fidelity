<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';
require_once __DIR__.'/MediaUtils.php';

if(empty($_SESSION['admin_id']) && empty($_SESSION['supper_admin_id'])){

  header('Location: ./admin/index.php');
}



$error = null;
if($_SERVER['REQUEST_METHOD'] === 'POST'){
  // Check to see if we have a file to be uploaded
  try{

    $name = $_POST['client_name'];
    $address = $_POST['client_address'];

    if(!$name || !strlen($name)){
      throw new Exception('Adding a client address requires a name');
    }

    if(!$address || !strlen($address)){
      throw new Exception('Adding a client address requires its IP address');
    }

    // has this address been added before now
    $name = trim($name);
    $address = trim($address);

    $hash = sha1(trim($address));
    $q1 =
    sprintf(
      'SELECT * FROM resource_media_ipaddress WHERE ipAddressHash = "%s"'
      , $hash);

    $check = queryData($q1);
    if($check && count($check)){
      throw new Exception(
        'Client '.$name.' with IP address '.$address.
        ' already exist in the system');
    }
    // if we get this far we need to store the records
    $q =
    sprintf(
      'INSERT INTO resource_media_ipaddress '.
      '(clientName,ipAddress,ipAddressHash) '.
      'VALUES("%s", "%s", "%s")'
      , $name
      , $address
      , $hash);

    executeQuery($q);
    // if we are done we need to redirect to the recource lists
    $uri = 'client_address_lists.php';
    header("Location:{$uri}");
    exit;
  }catch(Exception $ex){
    $error = $ex->getMessage();
  }
}
// pull resources from the
$title = 'Add Client IP Address';
include_once 'header.php';
?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:60%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Add Client IP</h2>
              </td>
              <td align="right">
                <a href="client_address_lists.php"><b>View Address List</b></a>&nbsp;&nbsp; - &nbsp;&nbsp;
                <a href="resources.php"><b>View Panel</b></a>
              </td>
            </tr>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <form method="post" action="client_address_add.php">
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
                               value=""
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
                               value=""
                               autocomplete="off"
                               maxlength="64"/>
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <button name="ipadd"
                                class="btn btn-primary">Save Address</button>
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
