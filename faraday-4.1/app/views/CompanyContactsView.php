<?php if ($contacts[0]) { ?>
  <?php $this->load->view('DataTableView', array('cols' => array_values($this->contactTableCols), 'mobileCols' => array(0, 2, 4), 'sourcePath' => "$entitySourcePath?type=$entityType&id=$entityId")); ?>
<?php } else { ?>
  <p class="bcz-no-data-msg h5">No contacts added yet.</p>
<?php } ?>