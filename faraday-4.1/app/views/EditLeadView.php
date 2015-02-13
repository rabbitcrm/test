<div class="clearfix">
  <h3>Edit Lead</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>leads/update/<?=$lead->lead_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9 no-padder">
                <div class="col-sm-3 m-t-mini">
                  <div class="btn-group col-xs-12 no-padder">
                    <button data-toggle="dropdown" class="btn btn-xs btn-white dropdown-toggle col-xs-12 form-control">
                      <span class="dropdown-label col-xs-11 text-left no-padder"><?=$lead->title?$lead->title:'Mr.'?></span>
                      <span class="caret"></span>
                    </button> 
                    <ul class="dropdown-menu dropdown-select col-xs-12">
                      <?php foreach($fields as $title) { if ($title->name_title) { ?>
                        <li><a href="#"><input type="radio" name="title" value="<?=$title->name_title?>" <? if ($title->name_title == $lead->title) { ?>checked="checked"<?php } ?>><?=$title->name_title?></a></li>
                      <?php } } ?>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-4 m-t-mini">
                  <input type="text" name="first_name" placeholder="First Name" id="lead_fname"  class="form-control" value="<?=$lead->first_name?>">
                </div>
                <div class="col-sm-5 m-t-mini">
                  <input type="text" name="last_name" placeholder="Last Name" id="lead_lname" class="form-control" value="<?=$lead->last_name?>">
                </div>
                <div id="error"></div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="company_name" data-required="true" class="form-control bcz-auto-complete" data-sourcepath="companies/suggestions" data-select-flag="true" value="<?=$lead->company_name?>">
                <input type="hidden" name="company_exists" value="">
                <div id="acresults"></div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Phone</label>
              <div class="col-lg-9">
                <input type="text" name="mobile" class="form-control" value="<?=$lead->mobile?>" data-type="number">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="email" name="email" placeholder="test@example.com" class="form-control" data-required="true" data-type="email" value="<?=$lead->email?>">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Status</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder m-t-mini">
                  <button data-toggle="dropdown" class="btn btn-xs btn-white dropdown-toggle col-xs-12 form-control">
                    <span class="dropdown-label col-xs-11 text-left no-padder"><?=$lead_status?$lead_status:$this->chooseOption?></span>
                    <span class="caret"></span>
                  </button> 
                  <ul class="dropdown-menu dropdown-select col-xs-12">
                    <li><a href="#"><input type="radio" name="lead_status" value=""></a></li>
                    <?php foreach($fields as $status) { if ($status->lead_status) { ?>
                      <li><a href="#"><input type="radio" name="lead_status" value="<?=$status->no?>" <? if ($status->lead_status == $lead->lead_status) { ?>checked="checked"<?php } ?>><?=$status->lead_status?></a></li>
                    <?php } } ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Lead Source<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="lead_source" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $source) { if ($source->source) { ?>
                      <option value="<?=$source->no?>" <? if ($source->source == $lead->lead_source) { ?>selected="selected"<?php } ?>><?=$source->source?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Assigned to</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <button data-toggle="dropdown" class="btn btn-xs btn-white dropdown-toggle col-xs-12 form-control">
                    <span class="dropdown-label col-xs-11 text-left no-padder"><?=$lead->name?$lead->name:($this->user->name?$this->user->name:$this->user->username)?></span>
                    <span class="caret"></span>
                  </button> 
                  <ul class="dropdown-menu dropdown-select col-xs-12">
                    <?php foreach($users as $user) { ?>
                      <li><a href="#"><input type="radio" name="lead_owner_id" value="<?=$user->user_id?>" <?php if (($user->user_id == $this->user->user_id) || ($user->user_id == $lead->lead_owner_id)) { ?> checked="checked"<?php } ?>><?=$user->name?></a></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <textarea name="lead_description" rows="3" class="form-control" data-trigger="keyup" data-rangelength="[20,1000]" data-required="true"><?=$lead->lead_description?></textarea>
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
                  <label class="col-lg-3 control-label">Alternate Email</label>
                  <div class="col-lg-9">
                    <input type="email" name="alt_email" placeholder="test@example.com" class="form-control" data-type="email" value="<?=$lead->alt_email?>">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Customer Type</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <button data-toggle="dropdown" class="btn btn-xs btn-white dropdown-toggle col-xs-12 form-control">
                        <span class="dropdown-label col-xs-11 no-padder text-left"><?=$customer_type?$customer_type:$this->chooseOption?></span>
                        <span class="caret"></span>
                      </button> 
                      <ul class="dropdown-menu dropdown-select col-xs-12">
                        <li><a href="#"><input type="radio" name="customer_type" value=""><?=$this->chooseOption?></a></li>
                        <?php foreach($fields as $customer_type) { if ($customer_type->customer_type) { ?>
                          <li><a href="#"><input type="radio" name="customer_type" value="<?=$customer_type->no?>" <? if ($customer_type->no == $lead->customer_type) { ?>checked="checked"<?php } ?>><?=$customer_type->customer_type?></a></li>
                        <?php } } ?>
                      </ul>
                    </div>
                  </div>
                </div>
                  <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Campaign</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                <select name="lead_application" class="select2-option" >
<?php print_r($campaigns); ?>
                <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($campaigns as $campaign) { ?>
                    
                      <option <?php if($lead->lead_application==$campaign->campaign_id) { echo 'selected="selected"'; } ?> value="<?=$campaign->campaign_id?>"  > <?=$campaign->campaign_name?></option>
                    <?php } ?>
                  </select>
                    <input type="hidden" value="<?=$lead->lead_application;?>" name="campaign" />
                  </div>
              </div>
            </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Address</label>
                  <div class="col-lg-9">
                    <textarea name="bill_addr" rows="3" class="form-control" data-trigger="keyup"><?=$lead->bill_addr?></textarea>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Address 1</label>
                  <div class="col-lg-9 no-padder">
                    <div class="col-sm-5 m-t-mini">
                      <input type="text" name="bill_city" placeholder="City" class="form-control" value="<?=$lead->bill_city?>">
                    </div>
                    <div class="col-sm-4 m-t-mini">
                      <input type="text" name="bill_state" placeholder="State" class="form-control" value="<?=$lead->bill_state?>">
                    </div>
                    <div class="col-sm-3 m-t-mini">
                      <input type="text" name="bill_postal_code" placeholder="Zip" class="form-control" value="<?=$lead->bill_postal_code?>" data-type="number">
                    </div>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Country<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="bill_country" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($fields as $country) { if ($country->country) { ?>
                          <option value="<?=$country->country?>" <? if ($country->country == $lead->bill_country) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
                        <?php } } ?>
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
        <button type="submit"  id="create_lead" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>
</div>