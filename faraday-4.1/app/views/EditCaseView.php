<div class="clearfix">
  <h3>Edit Ticket</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>cases/update/<?=$case->case_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
          
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Ticket No</label>
              <div class="col-lg-9">
                <input type="text"  class="form-control"  readonly="readonly"  value="<?=$case->case_no?>">
                
              </div>
            </div>
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Ticket<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="case_title" class="form-control" data-required="true" value="<?=$case->case_title?>">
              </div>
            </div>
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              
                            <div class="col-lg-9">
                            <div class="btn-group col-xs-11 no-padder remove_acc_opp">
              <div class="remove_acc">  <a class="icon-remove-sig" href="#"><i class="icon-remove-sign icon-large m-r-small"></i></a>
          </div>
          
                    <input type="hidden" id="deal_company_id" name="company_id" class="form-control" value="<?=$case->company_id?>">
                <input type="text" id="opp_company_name" data-required="true" class="input-sm form-control dropdown-toggle" data-toggle="dropdown"  value="<?=$case->company_name?>" autocomplete="off">
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
          
                    <input type="hidden" id="deal_contact_id" name="contact_id" class="form-control"  value="<?=$case->contact_id?>">
                <input type="text" id="opp_contact_name" data-required="true" class="input-sm form-control dropdown-toggle" data-toggle="dropdown"  value="<?=$case->contact_name?>" autocomplete="off">
                <ul class="dropdown-menu-opp-con hide"></ul>
               
              <div class="pull-right">  </div>
              </div>
               <div style="clear:both"></div>
                <div id="error1"></div>
              </div>
            
            </div>
            
            
            
            <?php /*?><div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="company_id" class="select2-option" id="company_id" data-required="true">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($companies as $companie) { ?>
                          <option value="<?=$companie->company_id?>" <? if ($companie->company_id == $case->company_id) { ?>selected="selected"<?php } ?>><?=$companie->company_name?></option>
                        <?php } ?>
                      </select>
                </div>
              </div>
            </div>
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="contact_id" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($contacts as $contact) { ?>
                      <option value="<?=$contact->contact_id?>" <? if ($contact->contact_id == $case->contact_id) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            
			<?php */?>
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Assign To</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="assign_to" class="select2-option">
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $case->assign_to) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
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
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $case_status) { if ($case_status->case_status) { ?>
                      <option value="<?=$case_status->no?>" <?php if ($case_status->no == $case->status) { ?> selected="selected"<?php } ?>><?=$case_status->case_status?></option>
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
                      <option value="<?=$priority->priority?>" <?php if ($priority->priority == $case->priority) { ?> selected="selected"<?php } ?>><?=$priority->priority?></option>
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
                      <option value="<?=$product->product_id?>" <?php if ($product->product_id == $case->case_product_id) { ?> selected="selected"<?php } ?>><?=$product->product_name?></option>
                    <?php } ?>
                  </select>
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
                      <option value="<?=$severity->severity?>" <?php if ($severity->severity == $case->severity) { ?> selected="selected"<?php } ?>><?=$severity->severity?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <textarea name="case_description" rows="3" class="form-control" data-required="true"><?=$case->case_description?></textarea>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Solution</label>
              <div class="col-lg-9">
                <textarea name="case_solution" rows="3" class="form-control"><?=$case->case_solution?></textarea>
              </div>
            </div>
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