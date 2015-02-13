<!-- .Create To Tickets modal -->
<div id="create_tickets_modal" class="modal fade">
  <form class="form-horizontal bcz-add-item-entity-form" method="post" data-validate="parsley" action="<?=base_url()?>cases/submit">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Create To Tickets</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>
              
              <div class="form-group">
              
                 <label class="col-lg-3 control-label">Ticket No<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="case_no" class="form-control" data-required="true"  readonly="readonly"  value="<?=$prefixsequence?>">
                 <input type="hidden" name="sequence" value="<?=$sequence?>">
                <input type="hidden" name="numbering_id" value="<?=$numbering_id?>">
              </div>
              
              </div>

              <div class="form-group">
              
                 <label class="col-lg-3 control-label">Ticket<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="case_title" class="form-control" data-required="true">
              </div>
              
              </div>
              
              <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <input type="text" class="form-control" value="<?=$contact->company_name?>" readonly="readonly" />
                </div>
              </div>
            </div>
            
            
            
            
              <div class="form-group">
                <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-9">
                <div class="btn-group col-xs-12 no-padder">
               <input type="text"  class="form-control" value="<?=$contact->contact_name;?>" readonly="readonly">
                </div>
              </div>
              </div>
              
              
              
                
              <div class="form-group">
            <label class="col-lg-3 control-label">Assigned to</label>
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
            
            
              <div class="form-group">
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
              
              
              <div class="form-group">
                <label class="col-lg-3 control-label">Product</label>
                <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                  <select name="case_product_id" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($products as $product) { ?>
                      <option value="<?=$product->product_id?>"><?=$product->product_name?></option>
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
                      <option value="<?=$severity->severity?>"><?=$severity->severity?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            
            
              <div class="form-group">
              
                <label class="col-lg-3 control-label">Summary<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <textarea data-required="true" name="case_description" rows="3" class="form-control"><?=$task->description?></textarea>
              </div>
              
               </div>

              
            </div>
          </section>
        </div>
        <div class="modal-footer">
         <input type="hidden" name="contact_id" value="<?=$contact->contact_id ?>" />
         <input type="hidden" name="associate_to" value="" />
          <input type="hidden" name="associate_id" value="" />
         <input type="hidden" name="modal_flag" value="1">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="add_task" type="submit" class="btn btn-sm btn-primary">Add</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->