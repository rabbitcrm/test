<!-- .modal -->
<div id="create_contact_modal" class="modal fade">
  <form class="form-horizontal bcz-add-item-entity-form" method="post" data-validate="parsley" action="<?=base_url()?>contacts/submit">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Add a new contact</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9 no-padder">
              <div class="col-sm-3 m-t-mini">
                <div class="btn-group col-xs-12 no-padder">
                  <button data-toggle="dropdown" class="btn btn-xs btn-white dropdown-toggle col-xs-12 form-control">
                    <span class="dropdown-label col-xs-11">Mr.</span>
                    <span class="caret"></span>
                  </button> 
                  <ul class="dropdown-menu dropdown-select col-xs-12">
                    <?php foreach($fields as $title) { if ($title->name_title) { ?>
                      <li><a href="#"><input type="radio" name="title" value="<?=$title->name_title?>"><?=$title->name_title?></a></li>
                    <?php } } ?>
                  </ul>
                </div>
              </div>
              <div class="col-sm-4 m-t-mini">
                <input type="text" name="first_name" placeholder="First Name" id="lead_fname"  class="form-control">
              </div>
              <div class="col-sm-5 m-t-mini">
                <input type="text" name="last_name" placeholder="Last Name"  class="form-control" id="lead_lname">
              </div>
              <div id="error"></div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
              <?php if(isset($deal->company_id)) { ?>
              <input type="hidden" name="company_id" value="<?=$deal->company_id ?>" />
              <input type="text" class="form-control" value="<?=$deal->company_name ?>" readonly="readonly">
              <?php } else { ?>
                <select name="company_id" data-required="true" class="select2-option" <? if ($add_deal_company_id) { ?> disabled="disabled"<?php } ?>>
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($companies as $company) { ?>
                    <option value="<?=$company->company_id?>" <?php if ($company->company_id == $add_deal_company_id) { ?>selected="selected"<?php }?>><?=$company->company_name?></option>
                  <?php } ?>
                </select>
                <? if ($add_deal_company_id) { ?><input type="hidden" name="company_id" class="form-control" value="<?=$add_deal_company_id?>"><?php } ?>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Phone<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="mobile" class="form-control" data-required="true" data-type="number">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="email" name="email" placeholder="test@example.com" class="form-control" data-required="true" data-type="email">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="associate_to" value="" />
          <input type="hidden" name="associate_id" value="" />
          <input type="hidden" name="modal_flag" value="1" />
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary" id="create_lead">Add</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->