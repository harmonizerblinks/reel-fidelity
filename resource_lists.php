<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';


if(empty($_SESSION['admin_id']) && empty($_SESSION['supper_admin_id'])){

  header('Location: ./admin/index.php');
}

$data = queryData('SELECT * FROM resource_media_files');

// pull resources from the
$title = 'Media Resource Lists';
include_once 'header.php';
?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:60%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Media Resource Lists</h2>
              </td>
              <td align="right">
                <a href="resource_upload.php"><b>Upload Resource</b></a> &nbsp;&nbsp; - &nbsp;&nbsp;
                <a href="resources.php"><b>View Panel</b></a>
              </td>
            </tr>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <?php if(count($data)):?>
                  <table width="100%">
                    <tbody>
                      <thead style="background-color:#222;color:#fff;font-size:13px;">
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Size</th>
                        <th></th>
                      </thead>
                      <?php $ctx =1; foreach($data as $row):?>
                        <tr  <?php $cssclass = (($ctx % 2) === 0)?'odd-row':'even-row';?> class="<?php echo $cssclass;?>" valign="top">
                          <td><?php echo $ctx;?></td>
                          <td><?php echo $row['fileName'];?></td>
                          <td><?php echo $row['fileType'];?></td>
                          <td><?php echo Utils::formatFileSize($row['fileSize']);?></td>
                          <td align="right">
                            <a href="delete_resource.php?id=<?php echo $row['id'];?>">Delete</a></td>
                        </tr>
                      <?php $ctx++; endforeach;?>
                    </tbody>
                  </table>
                <?php else:?>
                  <span style="text-align:center;color:maroon;display:block;">There are no media resources to list</span>
                <?php endif;?>
                <div style="clear:both;?>"></div>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
