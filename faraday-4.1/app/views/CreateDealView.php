<div class="clearfix">
  <h3>Add Opportunity</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>deals/submit">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Opp.Name<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="deal_name" id="deal_name" data-required="true" class="form-control"  value="" autocomplete="off">
                 <div id="error2"></div>
              </div>
             
            </div>
        
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              
                            <div class="col-lg-9">
                            <div class="btn-group col-xs-11 no-padder remove_acc_opp">
              <div class="remove_acc">  <a class="icon-remove-sig" href="#"><i class="icon-remove-sign icon-large m-r-small"></i></a>
          </div>
          
                    <input type="hidden" id="deal_company_id" name="deal_company_id" class="form-control">
                <input type="text" id="opp_company_name" data-required="true" class="input-sm form-control dropdown-toggle" name="opp_company_name" data-toggle="dropdown"  value="" autocomplete="off">
                <ul class="dropdown-menu-opp hide"></ul>
               
              <div class="pull-right">  <button class="btn btn-inverse btn-xs pull-right create_account_deal_modal bcz-active-btn" data-associate-to="deal" type="button" ><i class="icon-plus"></i> </button></div>
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
          
                    <input type="hidden" id="deal_contact_id" name="deal_contact_id" class="form-control">
                <input type="text" id="opp_contact_name" data-required="true" class="input-sm form-control dropdown-toggle" name="opp_contact_name" data-toggle="dropdown"  value="" autocomplete="off">
                <ul class="dropdown-menu-opp-con hide"></ul>
               
              <div class="pull-right">  <button class="btn btn-inverse btn-xs pull-right bcz-add-item-entity bcz-active-btn" data-associate-to="deal" data-associate-id="#" type="button" data-href="#create_contact_deal_modal"><i class="icon-plus"></i> </button></div>
              </div>
               <div style="clear:both"></div>
                <div id="error1"></div>
              </div>
              <?php /*?><div class="col-lg-9">
                <div class="btn-group col-xs-11 no-padder">
                  <select name="deal_contact_id" data-required="true" id="deal_contact_id" class="select2-option" <? if ($_SESSION['dealContact']) { ?>disabled="disabled"<?php } ?>>
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($contacts as $contact) { ?>
                      <option value="<?=$contact->contact_id?>" <? if ($contact->contact_id == $_SESSION['dealContact']) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                    <?php } ?>
                  </select>
                </div>
                <div class="pull-right"><a href="<?=base_url()?>deals/addcontact" class="btn btn-inverse btn-xs"> <i class="icon-plus icon-large"></i> </a></div>
                <? if ($_SESSION['dealContact']) { ?><input type="hidden" name="deal_contact_id" class="form-control" value="<?=$_SESSION['dealContact']?>"><?php } ?>
              </div><?php */?>
            </div>
            <div class="form-group m-b-small">
            
              <label class="col-lg-3 control-label">Opp.Value<?php if($currency_freeze['currency']!=""){ ?>(<?=$currency_freeze['currency']?>)<?php } ?><?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="deal_amount" data-required="true" class="form-control" data-type="number" id="deal_amount" >
                 <div id="error3"></div>
              </div>
             
            </div>
          </div>

          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Stage<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">  
                <div class="btn-group col-xs-12 no-padder">
                  <select name="stage" id="stage" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $stage) { if ($stage->stage) { ?>
                      <option value="<?=$stage->no?>"><?=$stage->stage?></option>
                    <?php } } ?>
                  </select>
                   <div id="error4"></div>
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
                <input type="text" name="exp_close" id="exp_close" data-required="true" class="form-control datepicker" value="<?=$this->today?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
                 <div id="error5"></div>
              </div>
             
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary</label>
              <div class="col-lg-9">
                <textarea name="summary" rows="3" class="form-control"></textarea>
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
                          <option value="<?=$source->source?>"><?=$source->source?></option>
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
                          <option value="<?=$industry->industry?>"><?=$industry->industry?></option>
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

                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($campaigns as $campaign) { ?>
                    
                      <option value="<?=$campaign->campaign_id?>"  > <?=$campaign->campaign_name?></option>
                    <?php } ?>
                  </select>
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
        <button type="submit" class="btn btn-primary m-l">Create</button>
      </div>
    </div>
  </form>
</div>