<div class="clearfix">
  <h3>Edit Campaign</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>
<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>campaign/update/<?=$campaign->campaign_id ?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Campaign Name<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="campaign_name" class="form-control" data-required="true" value="<?=$campaign->campaign_name ?>" >
              </div>
            </div>
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Assigned to<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="owner_id" class="select2-option" data-required="true">
                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $campaign->owner_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                    <?php } ?>
                  </select>
                  </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Campaign Type<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="campaign_type" class="select2-option" data-required="true">
                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($campaign_types as $campaign_type) { ?>
                      <option  <?php if($campaign->campaign_type==$campaign_type->campaign_type) {echo 'selected="selected"'; }?> value="<?=$campaign_type->no?>"> <?=$campaign_type->campaign_type?></option>
                    <?php } ?>
                  </select>
                  </div>
                
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Target Audience</label>
              <div class="col-lg-9">
                <input type="text" name="target_audience" class="form-control" value="<?=$campaign->target_audience ?>"  >
              </div>
            </div>
        
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Campaign Status<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
               
                <div class="btn-group col-xs-12 no-padder">
                <select name="campaign_status" class="select2-option" data-required="true">
                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($campaign_status as $status) { ?>
                      <option  <?php if($campaign->campaign_status==$status->campaign_status) {echo 'selected="selected"'; }?> value="<?=$status->no?>"> <?=$status->campaign_status?></option>
                    <?php } ?>
                  </select>
                  </div>
              </div>
            </div>
            
            
            
          </div>
          
          
          <div class="col-sm-6">
            
            
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Product<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                 <input type="text" name="product" class="form-control" data-required="true"  value="<?=$campaign->product ?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Exp.Close Date</label>
              <div class="col-lg-9">
           <input type="text" name="closedate" id="closedate"class="form-control datepicker parsley-validated" data-date-format="dd-mm-yyyy" placeholder="-- Choose Date --" value="<?=$campaign->closedate?date('d-m-Y', strtotime($campaign->closedate)):''?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Target Size</label>
              <div class="col-lg-9">
                <input type="text" name="target_size" class="form-control" value="<?=$campaign->target_size ?>">
              </div>
            </div>
           
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <textarea name="description" rows="3" class="form-control" data-trigger="keyup" data-rangelength="[20,1000]" data-required="true"><?=$campaign->description ?></textarea>
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
              <label class="col-lg-3 control-label">Exp.Sales Count</label>
              <div class="col-lg-9">
                <input type="text" name="sales_count" class="form-control" value="<?=$campaign->sales_count ?>">
              </div>
            </div>
                  <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Sponsor</label>
              <div class="col-lg-9">
                <input type="text" name="sponsor" class="form-control" value="<?=$campaign->sponsor ?>" >
              </div>
            </div>
            
          		  <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Budget Cost</label>
              <div class="col-lg-9">
                <input type="text" name="cost" class="form-control" value="<?=$campaign->cost ?>"  >
              </div>
            </div>
            
                <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Exp.Response</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                <select name="response" class="select2-option" >
                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($responses as $response) { ?>
                      <option value="<?=$response->no?>" <?php if($campaign->response==$response->no) {echo 'selected="selected"'; }?> > <?=$response->campaign_response?></option>
                    <?php } ?>
                  </select>
                  </div>
              </div>
            </div>
                
                
                
                
                
              </div>
              
              <div class="col-sm-6">
               <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Num Sent</label>
              <div class="col-lg-9">
                <input type="text" name="num_sent" class="form-control" value="<?=$campaign->num_sent ?>" >
              </div>
            </div>
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Exp.Response Count</label>
              <div class="col-lg-9">
                <input type="text" name="response_count" class="form-control" value="<?=$campaign->response_count ?>">
              </div>
            </div>
            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Expected ROI</label>
              <div class="col-lg-9">
                <input type="text" name="roi" class="form-control" value="<?=$campaign->roi ?>">
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
        <button type="submit" class="btn btn-primary m-l" id="create_campaign">Update</button>
      </div>
    </div>
  </form>
</div>