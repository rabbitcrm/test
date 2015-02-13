<div class="clearfix">
  <h3>Edit Account</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>companies/update/<?=$company->company_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="company_name" data-required="true" class="form-control" value="<?=$company->company_name?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Customer Type</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="com_cust_type" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $customer_type) { if ($customer_type->customer_type) { ?>
                      <option value="<?=$customer_type->no?>" <? if ($customer_type->no == $company->com_cust_type) { ?>selected="selected"<?php } ?>><?=$customer_type->customer_type?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Phone<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="phone" data-required="true" class="form-control" value="<?=$company->phone?>" data-type="number">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Assigned to</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="assign_to" class="select2-option">
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($company->assign_to == $user->user_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Email</label>
              <div class="col-lg-9">
                <input type="text" name="com_email" class="form-control" value="<?=$company->com_email?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Website</label>
              <div class="col-lg-9">
                <input type="text" name="website" class="form-control" value="<?=$company->website?>">
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
                      <input type="text" name="fax" class="form-control" value="<?=$company->fax?>" data-type="number">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Ownership</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="com_ownership" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $com_ownership) { if ($com_ownership->com_ownership) { ?>
                            <option value="<?=$com_ownership->com_ownership?>" <? if ($com_ownership->com_ownership == $company->com_ownership) { ?>selected="selected"<?php } ?>><?=$com_ownership->com_ownership?></option>
                          <?php } } ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Employees</label>
                    <div class="col-lg-9">
                      <input type="text" name="com_employees" class="form-control" value="<?=$company->com_employees?>">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Annual Revenue</label>
                    <div class="col-lg-9">
                      <input type="text" name="com_revenue" class="form-control" value="<?=$company->com_revenue?>">
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
                            <option value="<?=$industry->industry?>" <? if ($industry->industry == $company->com_industry) { ?>selected="selected"<?php } ?>><?=$industry->industry?></option>
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
                            <option value="<?=$application->application?>" <? if ($application->application == $company->com_application) { ?>selected="selected"<?php } ?>><?=$application->application?></option>
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
                          <input type="checkbox" name="is_supplier" value="1" <? if ($company->is_supplier) { ?>checked="checked"<?php } ?>>
                          <i class="icon-unchecked checked"></i>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Summary</label>
                    <div class="col-lg-9">
                      <textarea name="com_description" rows="3" class="form-control" data-trigger="keyup"><?=$company->com_description?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-6 billing-address-fields m-t-large">                  
                  <p class="h5 m-t-small m-b-small">Address Information</p>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing Address</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_address" class="form-control" value="<?=$company->bill_address?>">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing City</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_city" class="form-control" value="<?=$company->bill_city?>">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing State</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_state" class="form-control" value="<?=$company->bill_state?>">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing Zip Code</label>
                    <div class="col-lg-9">
                      <input type="text" name="bill_postal_code" class="form-control" value="<?=$company->bill_postal_code?>">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Billing Country</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="bill_country" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $country) { if ($country->country) { ?>
                            <option value="<?=$country->country?>" <? if ($country->country == $company->bill_country) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
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
                      <input type="text" name="ship_address" class="form-control" value="<?=$company->ship_address?>">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping City</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_city" class="form-control" value="<?=$company->ship_city?>">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping State</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_state" class="form-control" value="<?=$company->ship_state?>">
                    </div>
                  </div>                
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping Zip Code</label>
                    <div class="col-lg-9">
                      <input type="text" name="ship_postal_code" class="form-control" value="<?=$company->ship_postal_code?>">
                    </div>
                  </div>
                  <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Shipping Country</label>
                    <div class="col-lg-9">
                      <div class="btn-group col-xs-12 no-padder">
                        <select name="ship_country" class="select2-option">
                          <option value=""><?=$this->chooseOption?></option>
                          <?php foreach($fields as $country) { if ($country->country) { ?>
                            <option value="<?=$country->country?>" <? if ($country->country == $company->ship_country) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
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
        <button type="submit" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>

</div>