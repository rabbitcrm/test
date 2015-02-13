<div class="row padder">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="tax">
      <header class="panel-heading h5">
        <strong>Tax</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'tax';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $tax) { 
          //   if ($tax->tax) {
          //     $this->load->view('SingleSettingView', array('id' => $tax->no, 'text' => $tax->tax));
          //   } 
          // } 
        ?>
      </div>  
    </section>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="currency">
      <header class="panel-heading h5">
        <strong>Currency</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'currency';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $currency) { 
          //   if ($currency->currency) {
          //     $this->load->view('SingleSettingView', array('id' => $currency->no, 'text' => $currency->currency));
          //   }
          // } 
        ?>
      </div>  
    </section>
  </div>
</div>