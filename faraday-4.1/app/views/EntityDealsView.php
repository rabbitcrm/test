<?php if ($deals[0]) { ?>
  <?php $this->load->view('DataTableView', array('cols' => array_values($this->dealTableCols), 'mobileCols' => array(0, 4, 5), 'sourcePath' => "$entitySourcePath?type=$entityType&id=$entityId")); ?>
<?php } else { ?>
  <p class="bcz-no-data-msg h5">No Opportunities added yet.</p>
<?php } ?>