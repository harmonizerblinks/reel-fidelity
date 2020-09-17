<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
// List the resources

// session_start();
// Just for redirecting to the resource file
require_once './helpers/helpersfunctions.php';
if(empty($_SESSION['admin_id']) && empty($_SESSION['supper_admin_id'])){

  header('Location: ./admin/index.php');
}

$title = 'Media Resource Panel';
include_once 'header.php';
?>

    <div class="wrappers" id="wrappers">
        <div id="container">
          <table style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:100%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Media Resource Panel</h2>
                <hr/>
                <center>
                <ul class="item-update-list">
                  <li>
                    <a href="client_address_lists.php"><i class="fa fa-cog"></i> Client IP List</a>
                  </li>
                  <li>
                    <a href="resource_lists.php"><i class="fa fa-cog"></i> Resource List</a>
                  </li>
                  <li>
                    <a href="resource_upload.php"><i class="fa fa-cog"></i> Resource Upload</a>
                  </li>
                </ul>
              </center>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
