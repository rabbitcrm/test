<div class="clearfix">
  <h3>Add Account</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>companies/submit">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="company_name" data-required="true" class="form-control bcz-auto-complete" data-sourcepath="companies/suggestions">
              
                <div id="acresults"></div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Customer Type</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="com_cust_type" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $customer_type) { if ($customer_type->customer_type) { ?>
                      <option value="<?=$customer_type->no?>"><?=$customer_type->customer_type?></option>
                    <?php } } ?>
                  </select>
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
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Fax</label>
                    <div class="col-lg-9">
                      <input type="text" name="fax" class="form-control" data-type="number">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Ownership</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="com_ownership" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $com_ownership) { if ($com_ownership->com_ownership) { ?>
                            <option value="<?=$com_ownership->com_ownership?>"><?=$com_ownership->com_ownership?></option>
                          <?php } } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Employees</label>
                    <div class="col-lg-9">
                      <input type="text" name="com_employees" class="form-control">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Annual Revenue</label>
                    <div class="col-lg-9">
                      <input type="text" name="com_revenue" class="form-control">
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Industry</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="com_industry" class="select2-option">
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
                        <select name="com_application" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $application) { if ($application->application) { ?>
                            <option value="<?=$application->application?>"><?=$application->application?></option>
                          <?php } } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-xs-3 control-label">Supplier</label>
                    <div class="col-xs-9">
                      <div class="checkbox">
                        <label class="checkbox-custom">
                          <input type="checkbox" name="is_supplier" value="1">
                          <i class="icon-unchecked checked"></i>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Summary</label>
                    <div class="col-lg-9">
                      <textarea name="com_description" rows="3" class="form-control" data-trigger="keyup"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 billing-address-fields m-t-large">                  
                  <p class="h5 m-t-mini m-b-small">Address Information</p>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing Address</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_address" class="form-control">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing City</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_city" class="form-control">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing State</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_state" class="form-control">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing Zip Code</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_postal_code" class="form-control">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing Country</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="bill_country" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $country) { if ($country->country) { ?>
                            <option value="<?=$country->country?>" <? if (strtolower($country->country) == 'india') { ?>selected="selected"<?php } ?>><?=$country->country?></option>
                          <?php } } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6 shipping-address-fields m-t-large">               
                  <div class="form-group m-b-small">
                    <div class="radio">
                      <label class="col-lg-12 radio-custom control-label" id="copy_billing_addr">
                        <input type="radio" name="copy_billing_addr">
                        <i class="icon-circle-blank"></i>
                        <span class="h5">Copy Billing Address</span>
                      </label>
                    </div>
                  </div>                  
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping Address</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_address" class="form-control">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping City</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_city" class="form-control">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping State</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_state" class="form-control">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping Zip Code</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_postal_code" class="form-control">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping Country</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="ship_country" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $country) { if ($country->country) { ?>
                            <option value="<?=$country->country?>" <? if (strtolower($country->country) == 'india') { ?>selected="selected"<?php } ?>><?=$country->country?></option>
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
      </div>
      <!-- / .accordion -->
    </div>

    <div class="col-sm-12 m-t">
      <div class="form-group m-b-small">
        <button type="submit" class="btn btn-primary m-l">Create</button>
      </div>
    </div>
  </form>

</div>