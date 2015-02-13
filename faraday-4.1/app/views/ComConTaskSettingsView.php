<div class="row padder">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="customer_type">
      <header class="panel-heading h5">
        <strong>Customer Type</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'customer_type';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $customer_type) { 
          //   if ($customer_type->customer_type) {
          //     $this->load->view('SingleSettingView', array('id' => $customer_type->no, 'text' => $customer_type->customer_type));
          //   } 
          // } 
        ?>
      </div>
    </section>
  </div>
<!-- 
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="cust_sati">
      <header class="panel-heading h5">
        <strong>Customer Satisfaction</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          // foreach($fields as $cust_sati) { 
          //   if ($cust_sati->cust_sati) {
          //     $this->load->view('SingleSettingView', array('id' => $cust_sati->no, 'text' => $cust_sati->cust_sati));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
-->  
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="com_ownership">
      <header class="panel-heading h5">
        <strong>Account Ownership</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'com_ownership';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $com_ownership) { 
          //   if ($com_ownership->com_ownership) {
          //     $this->load->view('SingleSettingView', array('id' => $com_ownership->no, 'text' => $com_ownership->com_ownership));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="industry">
      <header class="panel-heading h5">
        <strong>Industry</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'industry';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $industry) { 
          //   if ($industry->industry) {
          //     $this->load->view('SingleSettingView', array('id' => $industry->no, 'text' => $industry->industry));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="name_title">
      <header class="panel-heading h5">
        <strong>Name Title</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'name_title';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="designation">
      <header class="panel-heading h5">
        <strong>Designation</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'designation';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $designation) { 
          //   if ($designation->designation) {
          //     $this->load->view('SingleSettingView', array('id' => $designation->no, 'text' => $designation->designation));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="country">
      <header class="panel-heading h5">
        <strong>Country</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'country';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $country) { 
          //   if ($country->country) {
          //     $this->load->view('SingleSettingView', array('id' => $country->no, 'text' => $country->country));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="task_status">
      <header class="panel-heading h5">
        <strong>Task Status</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'task_status';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $task_status) { 
          //   if ($task_status->task_status) {
          //     $this->load->view('SingleSettingView', array('id' => $task_status->no, 'text' => $task_status->task_status));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="task_type">
      <header class="panel-heading h5">
        <strong>Task Type</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'task_type';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $task_type) { 
          //   if ($task_type->task_type) {
          //     $this->load->view('SingleSettingView', array('id' => $task_type->no, 'text' => $task_type->task_type));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
</div>