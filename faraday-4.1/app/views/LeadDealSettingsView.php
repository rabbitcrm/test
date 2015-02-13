<div class="row padder">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="lead_status">
      <header class="panel-heading h5">
        <strong>Lead Status</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'lead_status';
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
    <section class="panel m-b-none bcz-block" data-type="source">
      <header class="panel-heading h5">
        <strong>Lead Source</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php  
          $type = 'source';
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
    <section class="panel m-b-none bcz-block" data-type="priority">
      <header class="panel-heading h5">
        <strong>Priority</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php  
          $type = 'priority';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $priority) { 
          //   if ($priority->priority) {
          //     $this->load->view('SingleSettingView', array('id' => $priority->no, 'text' => $priority->priority));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="stage">
      <header class="panel-heading h5">
        <strong>Opportunity Stage</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php  
          $type = 'stage';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
			
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order,'probability' => $item->probability));
          }

          // foreach($fields as $stage) { 
          //   if ($stage->stage) {
          //     $this->load->view('SingleSettingView', array('id' => $stage->no, 'text' => $stage->stage, 'probability' => $stage->probability));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
  
</div>