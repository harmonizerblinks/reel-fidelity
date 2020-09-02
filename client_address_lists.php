<?php
require_once __DIR__ . '/conf/AppConfig.class.php';
require_once __DIR__ . '/src/Startup.class.php';
require_once __DIR__.'/startup.php';

$addresses = queryData('SELECT * FROM resource_media_ipaddress');

// pull resources from the
$title = 'Client IP Addresses';
include_once 'header.php';
?>
    <div class="wrappers" id="wrappers">
        <div id="container">
          <table border="0" style="margin-top:80px;margin-left:auto;margin-right:auto;width:920px;border-bottom:1px solid #d9d8d7;">
            <tr>
              <td style="width:60%;">
                <h2 style="font-size:11;padding:3px;margin:3px;">Client IP Addresses</h2>
              </td>
              <td align="right">
                <a href="client_address_add.php"><b>Add Client IP</b></a> &nbsp;&nbsp;-&nbsp;&nbsp;
                <a href="resources.php"><b>View Panel</b></a>
              </td>
            </tr>
            <tr>
              <td style="padding:10px;" colspan="2">
                <hr/>
                <div style="clear:both;?>"></div>
                <?php if(count($addresses)):?>
                  <table width="100%">
                    <tbody>
                      <thead style="background-color:#222;color:#fff;font-size:13px;">
                        <th>#</th>
                        <th>Name</th>
                        <th>IP</th>
                        <th>Schedule</th>
                        <th></th>
                      </thead>
                      <?php $ctx =1; foreach($addresses as $row):?>
                        <tr  <?php $cssclass = (($ctx % 2) === 0)?'odd-row':'even-row';?> class="<?php echo $cssclass;?>" valign="top">
                          <td><?php echo $ctx;?></td>
                          <td><?php echo $row['clientName'];?></td>
                          <td><?php echo $row['ipAddress'];?></td>
                          <td><a href="list_resource_schedule.php?cid=<?php echo $row['id'];?>">View Schedule</a></td>
                          <td align="right">
                            <a href="delete_address.php?id=<?php echo $row['id'];?>">Delete</a>
                            - <a href="edit_address.php?id=<?php echo $row['id'];?>">Edit</a></td>
                        </tr>
                      <?php $ctx++; endforeach;?>
                    </tbody>
                  </table>
                <?php else:?>
                  <span style="text-align:center;color:maroon;display:block;">There are no client ip addresses to view</span>
                <?php endif;?>
                <div style="clear:both;?>"></div>
              </td>
            </tr>
          </table>
        </div>
      </div>
</body>
</html>
