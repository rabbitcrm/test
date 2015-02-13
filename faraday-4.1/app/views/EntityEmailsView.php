<?php if ($emails[0]) { ?>
  <?php $this->load->view('DataTableView', array('cols' => array_values($this->emailTableCols), 'mobileCols' => array(0, 1, 4), 'sourcePath' => "emails/getentityemailsjson?type=$entityType&id=$entityId")); ?>
<?php } else { ?>
  <p class="bcz-no-data-msg h5">No emails sent yet.</p>
<?php } ?>