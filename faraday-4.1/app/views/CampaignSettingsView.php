<div class="row padder">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="campaign_type">
      <header class="panel-heading h5">
        <strong>Campaign Type</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'campaign_type';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $status) { 
          //   if ($status->lead_status) {
          //     $this->load->view('SingleSettingView', array('id' => $status->no, 'text' => $status->lead_status));
          //   } 
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="campaign_status">
      <header class="panel-heading h5">
        <strong>Campaign Status</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php  
          $type = 'campaign_status';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $source) { 
          //   if ($source->source) {
          //     $this->load->view('SingleSettingView', array('id' => $source->no, 'text' => $source->source));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="campaign_response">
      <header class="panel-heading h5">
        <strong>Campaign Response</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php  
          $type = 'campaign_response';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }
        ?>
      </div>  
    </section>
  </div>
  
  
</div>