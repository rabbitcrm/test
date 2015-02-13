<div class="row padder">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="quote_stage">
      <header class="panel-heading h5">
        <strong>Quote Stage</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'quote_stage';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $quote_stage) { 
          //   if ($quote_stage->quote_stage) {
          //     $this->load->view('SingleSettingView', array('id' => $quote_stage->no, 'text' => $quote_stage->quote_stage));
          //   } 
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="quote_validity">
      <header class="panel-heading h5">
        <strong>Quote Validity</strong>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'quote_validity';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order, 'noDelete' => true));
          }
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="quote_delivery">
      <header class="panel-heading h5">
        <strong>Delivery Lead Time</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'quote_delivery';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order, 'noDelete' => false));
          }

          // foreach($fields as $quote_delivery) { 
          //   if ($quote_delivery->quote_delivery) {
          //     $this->load->view('SingleSettingView', array('id' => $quote_delivery->no, 'text' => $quote_delivery->quote_delivery));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
<!--  
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="quote_delay_reason">
      <header class="panel-heading h5">
        <strong>Delay Reason</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          // foreach($fields as $quote_delay_reason) { 
          //   if ($quote_delay_reason->quote_delay_reason) {
          //     $this->load->view('SingleSettingView', array('id' => $quote_delay_reason->no, 'text' => $quote_delay_reason->quote_delay_reason));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
-->  
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="quote_carrier">
      <header class="panel-heading h5">
        <strong>Shipping</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'quote_carrier';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $quote_carrier) { 
          //   if ($quote_carrier->quote_carrier) {
          //     $this->load->view('SingleSettingView', array('id' => $quote_carrier->no, 'text' => $quote_carrier->quote_carrier));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="quote_payment">
      <header class="panel-heading h5">
        <strong>Payment Terms</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'quote_payment';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $quote_payment) { 
          //   if ($quote_payment->quote_payment) {
          //     $this->load->view('SingleSettingView', array('id' => $quote_payment->no, 'text' => $quote_payment->quote_payment));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="case_status">
      <header class="panel-heading h5">
        <strong>Ticket Status</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'case_status';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $case_status) { 
          //   if ($case_status->case_status) {
          //     $this->load->view('SingleSettingView', array('id' => $case_status->no, 'text' => $case_status->case_status));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="severity">
      <header class="panel-heading h5">
        <strong>Severity</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'severity';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $severity) { 
          //   if ($severity->severity) {
          //     $this->load->view('SingleSettingView', array('id' => $severity->no, 'text' => $severity->severity));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="so_stage">
      <header class="panel-heading h5">
        <strong>Sales Order Stage</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'so_stage';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $quote_stage) { 
          //   if ($quote_stage->quote_stage) {
          //     $this->load->view('SingleSettingView', array('id' => $quote_stage->no, 'text' => $quote_stage->quote_stage));
          //   } 
          // } 
        ?>
      </div>  
    </section>
  </div>
</div>