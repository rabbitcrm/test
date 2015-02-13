<!-- .modal -->
<div id="create_deal_modal" class="modal fade">
  <form class="form-horizontal bcz-add-item-entity-form" method="post" data-validate="parsley" action="<?php echo base_url()?>deals/submit">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Add a new Opportunity</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Opp.Name<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="deal_name" data-required="true" class="form-control">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="deal_company_id" data-required="true" class="select2-option" <? if($add_deal_company_id){?>disabled="disabled"<?php } ?>>
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($companies as $company) { ?>
                    <option value="<?=$company->company_id?>" <?php if ($company->company_id == $add_deal_company_id) { ?>selected="selected"<?php } ?>><?=$company->company_name?></option>
                  <?php } ?>
                </select>
                <? if ($add_deal_company_id) { ?><input type="hidden" name="deal_company_id" class="form-control" value="<?=$add_deal_company_id?>"><?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="deal_contact_id" data-required="true" class="select2-option" <? if ($add_deal_contact_id) { ?>disabled="disabled"<?php } ?>>
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($contacts as $contact) { ?>
                    <option value="<?=$contact->contact_id?>" <?php if ($contact->contact_id == $add_deal_contact_id) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                  <?php } ?>
                </select>
                <? if ($add_deal_contact_id) { ?><input type="hidden" name="deal_contact_id" class="form-control" value="<?=$add_deal_contact_id?>"><?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Opp.Value<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="deal_amount" data-required="true" class="form-control" data-type="number">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Stage<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">  
              <div class="btn-group col-xs-12 no-padder">
                <select name="stage" class="select2-option" data-required="true">
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($fields as $stage) { if ($stage->stage) { ?>
                    <option value="<?=$stage->no?>"><?=$stage->stage?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Assigned to</label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="deal_owner_id" class="select2-option">
                  <?php foreach($users as $user) { ?>
                    <option value="<?=$user->user_id?>" <?php if ($user->user_id == $this->user->user_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Close Date<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="exp_close" data-required="true" class="form-control datepicker" value="<?=$this->today?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="associate_to" value="" />
          <input type="hidden" name="associate_id" value="" />
          <input type="hidden" name="modal_flag" value="1" />
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="add_task" type="submit" class="btn btn-sm btn-primary">Add</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->