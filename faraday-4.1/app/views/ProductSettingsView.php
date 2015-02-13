<div class="row padder">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">
    <section class="panel m-b-none bcz-block" data-type="product_category">
      <header class="panel-heading h5">
        <strong>Product Category</strong>
        <a href="#add_setting_modal" data-toggle="modal" data-action="add"><i class="icon-plus-sign icon-large pull-right"></i></a>
      </header>

      <div class="list-group text-left scrollbar">
        <?php 
          $type = 'product_category';
          $items = arrangeSettings($fields, $type);
          foreach ($items as $item) {
            $this->load->view('SingleSettingView', array('id' => $item->no, 'text' => $item->$type, 'order' => $item->sort_order));
          }

          // foreach($fields as $product_category) {
          //   if ($product_category->product_category) {
          //     $this->load->view('SingleSettingView', array('id' => $product_category->no, 'text' => $product_category->product_category));
          //   } 
          // } 
        ?>
      </div>  
    </section>
  </div>
</div>