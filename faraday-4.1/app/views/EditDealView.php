<div class="clearfix">
  <h3>Edit Opportunities</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>deals/update/<?=$deal->deal_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Opp.Name<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="deal_name" data-required="true" class="form-control" value="<?=$deal->deal_name?>">
              </div>
            </div>
            
            
       <?php /*?>     <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="deal_company_id" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($companies as $company) { ?>
                      <option value="<?=$company->company_id?>" <? if ($company->company_id == $deal->deal_company_id) { ?>selected="selected"<?php } ?>><?=$company->company_name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="deal_contact_id" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($contacts as $contact) { ?>
                      <option value="<?=$contact->contact_id?>" <? if ($contact->contact_id == $deal->deal_contact_id) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div><?php */?>
            
            
            
                  <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              
                            <div class="col-lg-9">
                            <div class="btn-group col-xs-11 no-padder remove_acc_opp">
              <div class="remove_acc">  <a class="icon-remove-sig" href="#"><i class="icon-remove-sign icon-large m-r-small"></i></a>
          </div>
          
                    <input type="hidden" id="deal_company_id" name="deal_company_id" class="form-control" value="<?=$deal->company_id ?>">
                <input type="text" id="opp_company_name" data-required="true" class="input-sm form-control dropdown-toggle" name="opp_company_name" data-toggle="dropdown"  value="<?=$deal->company_name ?>" autocomplete="off">
                <ul class="dropdown-menu-opp hide"></ul>
               
              <div class="pull-right">  <button class="btn btn-inverse btn-xs pull-right bcz-add-item-entity bcz-active-btn" data-associate-to="deal" data-associate-id="#" type="button" data-href="#create_account_deal_modal"><i class="icon-plus"></i> </button></div>
              <div style="clear:both"></div>
              <div id="error"></div>
              </div>
              </div>
            </div>  
              
              
              
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              
              
              <div class="col-lg-9">
                            <div class="btn-group col-xs-11 no-padder remove_con_opp">
              <div class="remove_acc">  <a class="icon-remove-sig-con" href="#"><i class="icon-remove-sign icon-large m-r-small"></i></a>
          </div>
          
                    <input type="hidden" id="deal_contact_id" name="deal_contact_id" class="form-control" value="<?=$deal->deal_contact_id ?>">
                <input type="text" id="opp_contact_name" data-required="true" class="input-sm form-control dropdown-toggle" name="opp_contact_name" data-toggle="dropdown"  value="<?php if($deal->first_name!=""){echo $deal->first_name." ".$deal->last_name; }else {echo $deal->last_name; }?>" autocomplete="off">
                <ul class="dropdown-menu-opp-con hide"></ul>
               
              <div class="pull-right">  <button class="btn btn-inverse btn-xs pull-right bcz-add-item-entity bcz-active-btn" data-associate-to="deal" data-associate-id="#" type="button" data-href="#create_contact_deal_modal"><i class="icon-plus"></i> </button></div>
              </div>
               <div style="clear:both"></div>
                <div id="error1"></div>
              </div>
         
            </div>
            
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Opp.Value<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input parsley-type="number" type="number" parsley-trigger="change" name="deal_amount" class="form-control" value="<?=$deal->deal_amount?>" data-required="true" data-type="number">
              </div>
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Stage<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                
                <div class="btn-group col-xs-12 no-padder">
                  <select name="stage" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $stage) { if ($stage->stage) { ?>
                      <option value="<?=$stage->no?>" <? if ($stage->no == $deal->stage) { ?>selected="selected"<?php } ?>><?=$stage->stage?></option>
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
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $deal->deal_owner_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Close Date<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="exp_close" data-required="true" class="form-control datepicker" value="<?=$deal->exp_close?date('d-m-Y', strtotime($deal->exp_close)):''?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary</label>
              <div class="col-lg-9">
                <textarea name="summary" rows="3" class="form-control"><?=$deal->summary?></textarea>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- .accordion -->
      <div class="panel-group m-b" id="accordion2">
        <div class="panel">
          <div class="panel-heading fieldset-head">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
              More
              <span class="caret pull-right"></span>
            </a>
          </div>
          <div id="collapseOne" class="panel-collapse collapse">
            <div class="panel-body">
              <div class="col-sm-6">
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Source</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="source" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($fields as $source) { if ($source->source) { ?>
                          <option value="<?=$source->source?>" <? if ($source->source == $deal->source) { ?>selected="selected"<?php } ?>><?=$source->source?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Industry</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="industry" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($fields as $industry) { if ($industry->industry) { ?>
                          <option value="<?=$industry->industry?>" <? if ($industry->industry == $deal->industry) { ?>selected="selected"<?php } ?>><?=$industry->industry?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                
                <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Campaign</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                <select name="deal_application" class="select2-option" >
<?php print_r($campaigns); ?>
                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($campaigns as $campaign) { ?>
                    
                      <option <?php if($deal->deal_application==$campaign->campaign_id) { echo 'selected="selected"'; } ?> value="<?=$campaign->campaign_id?>"  > <?=$campaign->campaign_name?></option>
                    <?php } ?>
                  </select>
                  <input type="hidden" value="<?=$deal->deal_application;?>" name="campaign" />
                  </div>
              </div>
            </div>
            
                
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- / .accordion -->
    </div>

    <div class="col-sm-12">
      <div class="form-group">
        <button type="submit" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>
</div>