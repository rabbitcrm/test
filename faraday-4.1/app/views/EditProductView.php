<div class="clearfix">
  <h3>Edit Product</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>products/update/<?=$product->product_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Product<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="product_name" class="form-control" data-required="true" value="<?=$product->product_name?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Part No<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="partno" class="form-control" data-required="true" value="<?=$product->partno?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Category<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="category" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $category) { if ($category->product_category) { ?>
                      <option value="<?=$category->no?>"<? if ($category->no == $product->category) { ?>selected="selected"<?php } ?>><?=$category->product_category?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Price<?php if($currency_freeze['currency']!=""){ ?>(<?=$currency_freeze['currency'];?>)<?php } ?><?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="price" class="form-control" data-required="true" value="<?=$product->price?>" data-type="number">
              </div>
            </div>
            <?php /*?><div class="form-group m-b-small">
              <label class="col-lg-3 control-label">USD Price<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text"  name="usd_price" class="form-control" data-required="true" value="<?=$product->usd_price?>" data-type="number">
              </div>
            </div><?php */?>
          </div>
        </div>
      </section>
    </div>

    <div class="col-sm-12">
      <div class="form-group m-b-small">
        <button type="submit" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>
</div>