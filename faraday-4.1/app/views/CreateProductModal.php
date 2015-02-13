<!-- .modal -->
<div id="create_product_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>products/submit">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Add a new product</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Product<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="product_name" class="form-control" data-required="true">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Part No<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="partno" class="form-control" data-required="true">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Category<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-sm-12 no-padder">
               <select name="category" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $category) { if ($category->product_category) { ?>
                      <option value="<?=$category->no?>"><?=$category->product_category?></option>
                    <?php } } ?>
                  </select>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Price<?php if($currency_freeze['currency']!=""){ echo "(".$currency_freeze['currency'].")"; }?><?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="price" class="form-control" data-required="true" data-type="number">
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <input type="hidden" name="modal_flag" value="1" />
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="add_product" type="submit" class="btn btn-sm btn-primary">Add</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<?php 


function getOrganizationcurrency_base ()
	{
		
		$currency_freeze= $this -> db-> select('*')-> where('org_id',$this->user->org_id)-> limit(1)-> get('organization_settings');
		$cur=$currency_freeze->row_array();
		foreach($cur as $freeze)
		{
			 $currency=$freeze->currency_freeze;
		}
		return $currency_freeze->row_array(); 

	} 
?>
<!-- / .modal -->