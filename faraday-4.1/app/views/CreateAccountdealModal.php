<!-- .modal -->
<div id="create_account_deal_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>companies/modalsubmit">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Add a new Account</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
               <input type="text" name="company_name" class="form-control company_name-opp"  data-required="true" >
              </div>
            </div>
          </div>
          
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Phone<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="phone" data-required="true" class="form-control" data-type="number">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Email</label>
            <div class="col-lg-9">
               <input type="text" name="com_email" class="form-control">
            </div>
          </div>
          
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Website</label>
            <div class="col-lg-9">
               <input type="text" name="website" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">

          <input type="hidden" name="modal_flag" value="1" />
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="add_account" type="submit" class="btn btn-sm btn-primary">Add</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->