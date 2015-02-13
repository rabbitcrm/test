<div class="clearfix">
  <h3>Add Ticket</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>cases/submit">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Ticket No<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="case_no" class="form-control" data-required="true"  readonly="readonly"  value="<?=$prefixsequence?>">
                 <input type="hidden" name="sequence" value="<?=$sequence?>">
                <input type="hidden" name="numbering_id" value="<?=$numbering_id?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Ticket<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="case_title" class="form-control" data-required="true">
              </div>
            </div>
            <?php /*?><div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Accounts<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <?php if ($_SESSION['caseCompanies']) { ?>
                    <input type="hidden" name="company_id" class="form-control" value="<?=$_SESSION['caseCompanies']?>">
                    <input type="text" class="form-control" readonly="readonly" value="<?=$_SESSION['caseCompanies']?>">
                  <?php } else { ?>
                      <select name="company_id" class="select2-option" id="company_id" data-required="true">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($companies as $companie) { ?>
                          <option value="<?=$companie->company_id?>" <? if ($companie->company_id == $_SESSION['caseContact']) { ?>selected="selected"<?php } ?>><?=$companie->company_name?></option>
                        <?php } ?>
                      </select>
                  <?php } ?>
                </div>
              </div>
            </div><?php */?>
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              
                            <div class="col-lg-9">
                            <div class="btn-group col-xs-11 no-padder remove_acc_opp">
              <div class="remove_acc">  <a class="icon-remove-sig" href="#"><i class="icon-remove-sign icon-large m-r-small"></i></a>
          </div>
          
                    <input type="hidden" id="deal_company_id" name="company_id" class="form-control">
                <input type="text" id="opp_company_name" data-required="true" class="input-sm form-control dropdown-toggle" data-toggle="dropdown"  value="" autocomplete="off">
                <ul class="dropdown-menu-opp hide"></ul>
               
              <div class="pull-right"> </div>
              <div style="clear:both"></div>
              <div id="error"></div>
              </div>
              </div>
            </div>
            
            
            
            <div style="clear:both"></div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              
              
              <div class="col-lg-9">
                            <div class="btn-group col-xs-11 no-padder remove_con_opp">
              <div class="remove_acc">  <a class="icon-remove-sig-con" href="#"><i class="icon-remove-sign icon-large m-r-small"></i></a>
          </div>
          
                    <input type="hidden" id="deal_contact_id" name="contact_id" class="form-control">
                <input type="text" id="opp_contact_name" data-required="true" class="input-sm form-control dropdown-toggle" data-toggle="dropdown"  value="" autocomplete="off">
                <ul class="dropdown-menu-opp-con hide"></ul>
               
              <div class="pull-right">  </div>
              </div>
               <div style="clear:both"></div>
                <div id="error1"></div>
              </div>
            
            </div>
            
            
            
            
            
    <?php /*?>        <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <?php if ($_SESSION['caseContact']) { ?>
                    <input type="hidden" name="contact_id" class="form-control" value="<?=$_SESSION['caseContact']?>">
                    <input type="text" class="form-control" readonly="readonly" value="<?=$_SESSION['caseContactName']?>">
                  <?php } else { ?>
                      <select name="contact_id" class="select2-option" data-required="true">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($contacts as $contact) { ?>
                          <option value="<?=$contact->contact_id?>" <? if ($contact->contact_id == $_SESSION['caseContact']) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                        <?php } ?>
                      </select>
                  <?php } ?>
                </div>
              </div>
            </div><?php */?>
            
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Assign To</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="assign_to" class="select2-option">
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $this->user->user_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Status</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="status" class="select2-option">
                    
                    <?php foreach($fields as $case_status) { if ($case_status->case_status) { ?>
                      <option value="<?=$case_status->no?>"><?=$case_status->case_status?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Priority<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="priority" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $priority) { if ($priority->priority) { ?>
                      <option value="<?=$priority->priority?>"><?=$priority->priority?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Product</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="case_product_id" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($products as $product) { ?>
                      <option value="<?=$product->product_id?>"><?=$product->product_name?></option>
                    <?php } ?>
                  </select>
<!--                  
                  <select name="no_product_id" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                  </select>
-->
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Severity</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="severity" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $severity) { if ($severity->severity) { ?>
                      <option value="<?=$severity->severity?>"><?=$severity->severity?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <textarea name="case_description" rows="3" class="form-control" data-required="true"></textarea>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-sm-12">
      <div class="form-group m-b-small">
        <button type="submit" class="btn btn-primary m-l">Create</button>
      </div>
    </div>
  </form>
</div>