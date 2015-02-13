<?php if ($tasks[0]) { ?>
  <?php $this->load->view('DataTableView', array('cols' => array_values($this->taskTableCols), 'mobileCols' => array(0, 2, 3), 'sourcePath' => "tasks/getentitytasksjson?type=$entityType&id=$entityId")); ?>
<?php } else { ?>
  <p class="bcz-no-data-msg h5">No tasks added yet.</p>
<?php } ?>