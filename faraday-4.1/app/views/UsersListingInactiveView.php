

<?php $this->load->view('DataTableView', array('cols' => array_merge(array_values($this->userTableCols), array('actions')), 'sourcePath' => "settings/getinactiveusersjson")); ?>

<?php //$this->load->view('DataTableView', array('urlFlag' => false)); ?>