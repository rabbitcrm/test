<div class="clearfix">
  <h3>Add Quote</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>quotes/submit">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Quote NO<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text"  data-required="true" readonly="readonly" class="form-control" value="<?=$prefixsequence?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Subject<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="subject" data-required="true" class="form-control" value="<?=$deal->deal_name?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Opportunity<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9 h5">
                <label class="control-label h5 text-primary"><?=$deal->deal_name?></label>
                <input type="hidden" name="deal_id" data-required="true" class="form-control" value="<?=$deal->deal_id?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <label class="control-label h5 text-primary"><?=$deal->company_name?></label>
                <input type="hidden" name="company_id" data-required="true" class="form-control" value="<?=$deal->deal_company_id?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="contact_id" data-required="true" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($contacts as $contact) { ?>
                      <option value="<?=$contact->contact_id?>" <? if ($contact->contact_id == $deal->deal_contact_id) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Quote Stage<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="quote_stage" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_stage) { if ($quote_stage->quote_stage) { ?>
                      <option value="<?=$quote_stage->no?>" <? if ($quote_stage->quote_stage == "Created") { ?>selected="selected"<?php } ?>><?=$quote_stage->quote_stage?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Delivery Period</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="delivery" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_delivery) { if ($quote_delivery->quote_delivery) { ?>
                      <option value="<?=$quote_delivery->quote_delivery?>"><?=$quote_delivery->quote_delivery?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Carrier</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="carrier" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_carrier) { if ($quote_carrier->quote_carrier) { ?>
                      <option value="<?=$quote_carrier->quote_carrier?>"><?=$quote_carrier->quote_carrier?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Payment Terms</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="payment" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_payment) { if ($quote_payment->quote_payment) { ?>
                      <option value="<?=$quote_payment->quote_payment?>"><?=$quote_payment->quote_payment?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Currency<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                 <?php /*?> <input type="text" name="quote_currency" data-required="true" class="form-control bcz-currency" value="<?php if($currency_freeze['currency']!=""){ ?><?=$currency_freeze['currency']?><?php } ?>" readonly><?php */?>
                  <select name="quote_currency" class="select2-option bcz-currency" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php $defaultCurrency = $this->user->settings->currency ? $this->user->settings->currency : $this->user->organization->currency; foreach($fields as $currency) { if ($currency->currency) { ?>
                      <option value="<?=$currency->currency?>" <? if ($currency->currency == $defaultCurrency) { ?>selected="selected"<?php } ?>><?=$currency->currency?></option>
                    <?php } } ?>
                  </select>
                </div>
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
              Address Information
              <span class="caret pull-right"></span>
            </a>
          </div>
          <div id="collapseOne" class="panel-collapse collapse in">
            <div class="panel-body">
              <div class="col-sm-6 billing-address-fields">                  
                <p class="h5 m-t-small m-b-small hidden-xs">&nbsp;</p>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Billing Address<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_addr" data-required="true" class="form-control" value="<?=$deal->bill_address?>">
                  </div>
                </div>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Billing City<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_city" data-required="true" class="form-control" value="<?=$deal->bill_city?>">
                  </div>
                </div>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Billing State<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_state" data-required="true" class="form-control" value="<?=$deal->bill_state?>">
                  </div>
                </div>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Billing Zip Code<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_pcode" data-required="true" class="form-control" value="<?=$deal->bill_postal_code?>">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Billing Country</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="bill_country" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php $billCountry = $deal->bill_country ? $deal->bill_country : 'India'; foreach($fields as $country) { if ($country->country) { ?>
                          <option value="<?=$country->country?>" <? if ($country->country == $billCountry) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 shipping-address-fields">               
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
                  <label class="col-lg-3 control-label">Shipping Address<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_addr" data-required="true" class="form-control" value="<?=$deal->ship_address?>">
                  </div>
                </div>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Shipping City<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_city" data-required="true" class="form-control" value="<?=$deal->ship_city?>">
                  </div>
                </div>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Shipping State<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_state" data-required="true" class="form-control" value="<?=$deal->ship_state?>">
                  </div>
                </div>                
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Shipping Zip Code<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_pcode" data-required="true" class="form-control" value="<?=$deal->ship_postal_code?>">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Shipping Country</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="ship_country" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php $shipCountry = $deal->ship_country ? $deal->ship_country : 'India'; foreach($fields as $country) { if ($country->country) { ?>
                          <option value="<?=$country->country?>" <? if ($country->country == $shipCountry) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
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

      <textarea name="terms" placeholder="Terms & Conditions" rows="2" class="form-control col-sm-12"><?=$terms_conditions; ?></textarea>
      <textarea name="q_description" placeholder="Remarks" rows="2" class="form-control col-sm-12 m-t"></textarea>

      <!-- .accordion -->
      <div class="panel-group m-b m-t" id="accordion3">
        <div class="panel">
          <div class="panel-heading fieldset-head">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseTwo">
              Items <strong class="text-danger select_currency_msg hide">You should specify the currency before selecting products</strong>
              <span class="caret pull-right"></span>
            </a>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse in">
            <div class="panel-body scroll scroll-x">
              <div class="row m-t-mini padder hidden-xs">
                <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini h5">Item<?=$this->mandatoryFieldIndicator?></div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Qty<?=$this->mandatoryFieldIndicator?></div>
                <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini h5">Price<?=$this->mandatoryFieldIndicator?></div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Disc val</div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Disc %</div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Tax Type </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Tax %</div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Amount<?=$this->mandatoryFieldIndicator?></div>
              </div>
              <div class="row m-t-mini padder bcz-quote-item">
                <div class="col-sm-3 text-left text-primary no-padder h4 visible-xs <?=$ikey?' m-t':''?>">
                  Item #<span class="item-index">1</span>
                </div>
                <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Item<?=$this->mandatoryFieldIndicator?></div>
                    <div class="btn-group col-xs-12">
                      <select name="id[]" data-required="true" class="select2-option col-xs-12 no-padder bcz-quote-product">
                        <option value=""><?=$this->chooseOption?></option>
                        <option value="skz_add_new_prod">-- Add a new product --</option>
                        <?php foreach($products as $product) { ?>
                          <option value="<?=$product->product_id?>" data-price="<?=$product->price?>" class="bcz-quote-product1" data-block="INR" id="<?=$product->product_id?>"  data-price-usd="<?=$product->usd_price?>"><?=$product->category . ' - ' . $product->product_name . ' - ' . $product->partno?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Qty<?=$this->mandatoryFieldIndicator?></div>
                    <div class="col-xs-12">
                      <input type="text" name="qty[]" data-required="true" class="form-control bcz-quote-item-field bcz-quote-qty" data-type="number">
                    </div>
                  </div>
                </div>
                <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Price<?=$this->mandatoryFieldIndicator?></div>
                    <div class="col-xs-12">
                      <input type="text" name="price[]" data-required="true"  class="form-control bcz-quote-item-field bcz-quote-price">
                    </div>
                  </div>
                </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Disc val</div>
                    <div class="col-xs-12">
                      <input type="text" name="discount[]" class="form-control bcz-quote-item-field bcz-quote-discount" data-type="number">
                    </div>
                  </div>
                </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Disc %</div>
                    <div class="col-xs-12">
                      <input type="text" name="dis_percent[]" class="form-control bcz-quote-item-field bcz-quote-discount1" data-type="number">
                    </div>
                  </div>
                </div>
                
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Tax Type</div>
                    <div class="btn-group col-xs-12">
                
                      <select name="tax_type[]" class="select2-option col-xs-12 no-padder " >
                          <option value="">No</option>
                        <?php foreach($fields as $tax_name) { if ($tax_name->tax_name) { ?>
                          <option value="<?=$tax_name->tax_name?>"><?=$tax_name->tax_name?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                
                
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Tax %</div>
                    <div class="btn-group col-xs-12">
                      <select name="vat[]" class="select2-option col-xs-12 no-padder bcz-quote-vat" >
                        <option value="0">0</option>
                        <?php foreach($fields as $tax) { if ($tax->tax) { ?>
                          <option value="<?=$tax->tax?>"><?=$tax->tax?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini ">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Amount<?=$this->mandatoryFieldIndicator?></div>
                    <div class="col-xs-12">
                      <input type="text" name="amount[]" data-required="true" class="form-control bcz-quote-item-field bcz-quote-amount" readonly="readonly">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-t-mini padder bcz-quote-item">
                <div class="col-sm-3 text-center padder-r-mini no-padder-l">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Summary</div>
                    <div class="col-xs-12">
                      <textarea name="desc[]" placeholder="Summary" rows="2" class="form-control col-xs-12 bcz-quote-item-field"></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-t padder">
                <div class="col-xs-3 padder-r-mini no-padder-l">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12">
                      <a href="#" id="add_quote_item" class="btn btn-default btn-xs">Add Item</a>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-t padder">    
                <div class="col-sm-4 pull-right no-padder">
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Frieght:</label>
                    <div class="col-sm-7">
                      <input type="text" name="frieght" class="form-control bcz-quote-items-frieght" data-type="number">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Installation:</label>
                    <div class="col-sm-7">
                      <input type="text" name="install" class="form-control bcz-quote-items-install" data-type="number">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Total:<?=$this->mandatoryFieldIndicator?></label>
                    <div class="col-sm-7">
                      <input type="text" name="total" class="form-control bcz-quote-items-total" data-required="true" readonly="readonly">
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
      <div class="form-group">
        <button type="submit" class="btn btn-primary m-l">Create</button>
      </div>
    </div>
      </form>
      
      <input type="hidden" id="base_currency" value="<?php if($currency_freeze['currency']!=""){ ?><?=$currency_freeze['currency']?><?php } ?>" />


  <!-- Dummy HTML Block for Quote Item -->
  <div class="hide" id="bcz_quote_dummy_item">
    <?php $this->load->view('SingleItemFieldsView', array('fields' => $fields, 'products' => $products)); ?>
  </div>

</div>