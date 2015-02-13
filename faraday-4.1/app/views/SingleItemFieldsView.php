<!-- Start: Dummy HTML Block for Quote Items -->
<div class="row m-t-mini padder bcz-quote-item">
  <div class="col-sm-3 text-left m-t text-primary no-padder h4 visible-xs">
    Item #<span class="item-index">2</span>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">                  
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Item<?=$this->mandatoryFieldIndicator?></div>
      <div class="btn-group col-xs-12">
        <select name="id[]" data-required="true" class="bcz-select2-option col-xs-12 no-padder bcz-quote-product">
          <option value=""><?=$this->chooseOption?></option>
          <?php foreach($products as $product) { ?>
            <option value="<?=$product->product_id?>" data-price="<?=$product->price?>" data-price-usd="<?=$product->usd_price?>"><?=$product->category . ' - ' . $product->product_name . ' - ' . $product->partno?></option>
          <?php } ?>
        </select>
      </div>
    </div>
  </div>
  <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Qty<?=$this->mandatoryFieldIndicator?></div>
      <div class="col-xs-12">
        <input type="text" name="qty[]" data-required="true" class="form-control bcz-quote-item-field bcz-quote-qty" data-type="number">
      </div>
    </div>
  </div>
  <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini">
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Price<?=$this->mandatoryFieldIndicator?></div>
      <div class="col-xs-12">
        <input type="text" name="price[]" data-required="true"  class="form-control bcz-quote-item-field bcz-quote-price">
      </div>
    </div>
  </div>
  <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Disc val</div>
      <div class="col-xs-12">
        <input type="text" name="discount[]" class="form-control bcz-quote-item-field bcz-quote-discount" data-type="number">
      </div>
    </div>
  </div>
  <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Disc %</div>
      <div class="col-xs-12">
        <input type="text" name="dis_percent[]" class="form-control bcz-quote-item-field bcz-quote-discount1" data-type="number">
      </div>
    </div>
  </div>
  
  <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">                  
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Tax Type</div>
      <div class="btn-group col-xs-12">
                
                      <select name="tax_type[]" class="bcz-select2-option col-xs-12 no-padder bcz-quote-vat1" data-required="true">
                          <option value="">No</option>
                        <?php foreach($fields as $tax_name) { if ($tax_name->tax_name) { ?>
                          <option value="<?=$tax_name->tax_name?>"><?=$tax_name->tax_name?></option>
                        <?php } } ?>
                      </select>
 </div>
    </div>
  </div>
 
  
  <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">                  
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Tax %</div>
      <div class="btn-group col-xs-12">
        <select name="vat[]" class="bcz-select2-option col-xs-12 no-padder bcz-quote-vat">
          <option value="0">0</option>
          <?php foreach($fields as $tax) { if ($tax->tax) { ?>
            <option value="<?=$tax->tax?>"><?=$tax->tax?></option>
          <?php } } ?>
        </select>
      </div>
    </div>
  </div>
  <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini ">
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Amount<?=$this->mandatoryFieldIndicator?></div>
      <div class="col-xs-12">
        <input type="text" name="amount[]" data-required="true" class="form-control bcz-quote-item-field bcz-quote-amount" readonly="readonly">
      </div>
    </div>
  </div>
  
</div>

<div class="row m-t-mini padder bcz-quote-item">
  <div class="col-sm-3 text-center padder-r-mini no-padder-l">                  
    <div class="form-group m-b-none">
      <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Summary</div>
      <div class="col-xs-12">
        <textarea name="desc[]" placeholder="Summary" rows="2" class="form-control col-xs-12 bcz-quote-item-field"></textarea>
      </div>
    </div>
  </div>
  <div class="col-sm-3 text-center padder-r-mini no-padder-l visible-xs">
    <div class="form-group m-b-none">
  	  <div class="col-xs-12 text-center padder-r-mini no-padder-l m-t-mini"><a href="#" class="btn btn-primary btn-xs bcz-btn-delete-quote-item pull-right">Delete</a></div>
    </div>
  </div>
  <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini hidden-xs"><a href="#" class="btn btn-primary btn-xs bcz-btn-delete-quote-item">Delete</a></div>
</div> 

<!-- End: Dummy HTML Block for Quote Items -->