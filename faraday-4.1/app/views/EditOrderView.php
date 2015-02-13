<div class="clearfix">
  <h3>Edit Sales Order</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>orders/update/<?=$order->so_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Subject<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="subject" data-required="true" class="form-control" value="<?=$order->subject?>">
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Opportunity</label>
              <div class="col-lg-9 h5">
                <label class="control-label h5 text-primary"><?=$order->deal_name?></label>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Account</label>
              <div class="col-lg-9">
                <label class="control-label h5 text-primary"><?=$order->company_name?></label>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Contact<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="contact_id" data-required="true" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($contacts as $contact) { ?>
                      <option value="<?=$contact->contact_id?>" <? if ($contact->contact_id == $order->contact_id) { ?>selected="selected"<?php } ?>><?=$contact->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Stage<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="so_stage" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $so_stage) { if ($so_stage->so_stage) { ?>
                      <option value="<?=$so_stage->no?>" <? if ($so_stage->no == $order->so_stage) { ?>selected="selected"<?php } ?>><?=$so_stage->so_stage?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Delivery Period</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="delivery" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_delivery) { if ($quote_delivery->quote_delivery) { ?>
                      <option value="<?=$quote_delivery->quote_delivery?>" <? if ($quote_delivery->quote_delivery == $order->delivery) { ?>selected="selected"<?php } ?>><?=$quote_delivery->quote_delivery?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Carrier</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="carrier" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_carrier) { if ($quote_carrier->quote_carrier) { ?>
                      <option value="<?=$quote_carrier->quote_carrier?>" <? if ($quote_carrier->quote_carrier == $order->carrier) { ?>selected="selected"<?php } ?>><?=$quote_carrier->quote_carrier?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Payment Terms</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="payment" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_payment) { if ($quote_payment->quote_payment) { ?>
                      <option value="<?=$quote_payment->quote_payment?>" <? if ($quote_payment->quote_payment == $order->payment) { ?>selected="selected"<?php } ?>><?=$quote_payment->quote_payment?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Estimated Delivery</label>
              <div class="col-lg-9">
                <input type="text" name="estimated_delivery" class="form-control datepicker" value="<?=$order->estimated_delivery?date('d-m-Y', strtotime($order->estimated_delivery)):''?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Committed Date</label>
              <div class="col-lg-9">
                <input type="text" name="committed_date" class="form-control datepicker" value="<?=($order->committed_date && $order->committed_date != '0000-00-00')?date('d-m-Y', strtotime($order->committed_date)):''?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Dispatch Date</label>
              <div class="col-lg-9">
                <input type="text" name="dispatch_date" class="form-control datepicker" value="<?=($order->dispatch_date && $order->dispatch_date != '0000-00-00')?date('d-m-Y', strtotime($order->dispatch_date)):''?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Delay Reason(if any)</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="delay_reason" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $quote_delay_reason) { if ($quote_delay_reason->quote_delay_reason) { ?>
                      <option value="<?=$quote_delay_reason->quote_delay_reason?>" <? if ($quote_delay_reason->quote_delay_reason == $order->delay_reason) { ?>selected="selected"<?php } ?>><?=$quote_delay_reason->quote_delay_reason?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Installed Date</label>
              <div class="col-lg-9">
                <input type="text" name="install_date" class="form-control datepicker" value="<?=($order->install_date && $order->install_date != '0000-00-00')?date('d-m-Y', strtotime($order->install_date)):''?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Customer Satisfaction</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="so_cust_sati" class="select2-option">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $cust_sati) { if ($cust_sati->cust_sati) { ?>
                      <option value="<?=$cust_sati->cust_sati?>" <? if ($cust_sati->cust_sati == $order->so_cust_sati) { ?>selected="selected"<?php } ?>><?=$cust_sati->cust_sati?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">P.O Reference<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="so_reference_po" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $po_reference) { if ($po_reference->po_reference) { ?>
                      <option value="<?=$po_reference->po_reference?>" <? if ($po_reference->po_reference == $order->so_reference_po) { ?>selected="selected"<?php } ?>><?=$po_reference->po_reference?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group mb-small">
              <label class="col-lg-3 control-label">Currency<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="so_currency" class="select2-option bcz-currency" disabled="disabled" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $currency) { if ($currency->currency) { ?>
                      <option value="<?=$currency->currency?>" <? if ($currency->currency == $order->so_currency) { ?>selected="selected"<?php } ?>><?=$currency->currency?></option>
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
                <p class="h5 m-t m-b-small hidden-xs">&nbsp;</p>
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Billing Address<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_addr" data-required="true" class="form-control" value="<?=$order->bill_addr?>">
                  </div>
                </div>                
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Billing City<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_city" data-required="true" class="form-control" value="<?=$order->bill_city?>">
                  </div>
                </div>                
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Billing State<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_state" data-required="true" class="form-control" value="<?=$order->bill_state?>">
                  </div>
                </div>                
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Billing Zip Code<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="bill_pcode" data-required="true" class="form-control" value="<?=$order->bill_pcode?>">
                  </div>
                </div>
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Billing Country</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="bill_country" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($fields as $country) { if ($country->country) { ?>
                          <option value="<?=$country->country?>" <? if ($country->country == $order->bill_country) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 shipping-address-fields">               
                <div class="form-group mb-small">
                  <div class="radio">
                    <label class="col-lg-12 radio-custom control-label" id="copy_billing_addr">
                      <input type="radio" name="copy_billing_addr">
                      <i class="icon-circle-blank"></i>
                      <span class="h5">Copy Billing Address</span>
                    </label>
                  </div>
                </div>                  
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Shipping Address<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_addr" data-required="true" class="form-control" value="<?=$order->ship_addr?>">
                  </div>
                </div>                
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Shipping City<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_city" data-required="true" class="form-control" value="<?=$order->ship_city?>">
                  </div>
                </div>                
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Shipping State<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_state" data-required="true" class="form-control" value="<?=$order->ship_state?>">
                  </div>
                </div>                
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Shipping Zip Code<?=$this->mandatoryFieldIndicator?></label>
                  <div class="col-lg-9">
                    <input type="text" name="ship_pcode" data-required="true" class="form-control" value="<?=$order->ship_pcode?>">
                  </div>
                </div>
                <div class="form-group mb-small">
                  <label class="col-lg-3 control-label">Shipping Country</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="ship_country" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($fields as $country) { if ($country->country) { ?>
                          <option value="<?=$country->country?>" <? if ($country->country == $order->ship_country) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
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

      <textarea name="terms" placeholder="Terms & Conditions" rows="2" class="form-control col-sm-12"><?=$order->terms?></textarea>
      <textarea name="so_description" placeholder="Remarks" rows="2" class="form-control col-sm-12 m-t"><?=$order->so_description?></textarea>

      <!-- .accordion -->
      <div class="panel-group m-b m-t" id="accordion3">
        <div class="panel">
          <div class="panel-heading fieldset-head">
            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseTwo">
              Items
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
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Tax Type</div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini h5">Tax %</div>
                <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini h5">Amount<?=$this->mandatoryFieldIndicator?></div>
              </div>

              <?php foreach ($order->items as $ikey => $item) { ?>
              <div class="row m-t-mini padder bcz-quote-item">
                <div class="col-sm-3 text-left text-primary no-padder h4 visible-xs <?=$ikey?' m-t':''?>">
                  Item #<span class="item-index"><?=$ikey?></span>
                </div>
                <div class="col-sm-3 text-center padder-r-mini no-padder-l m-t-mini">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Item<?=$this->mandatoryFieldIndicator?></div>
                    <div class="btn-group col-xs-12">
                      <select name="id[]" class="select2-option col-xs-12 no-padder bcz-quote-product">
                        
                         <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($products as $product) { ?>
                          <option value="<?=$product->product_id?>" data-price="<?=$product->price?>" data-price-usd="<?=$product->usd_price?>" <? if ($product->product_id == $item['id']) { ?>selected="selected"<?php } ?>><?=$product->category . ' - ' . $product->product_name . ' - ' . $product->partno?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Qty<?=$this->mandatoryFieldIndicator?></div>
                    <div class="col-xs-12">
                      <input type="text" name="qty[]" class="form-control bcz-quote-item-field bcz-quote-qty" value="<?=$item['qty']?>" data-type="number">
                    </div>
                  </div>
                </div>
                <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Price<?=$this->mandatoryFieldIndicator?></div>
                    <div class="col-xs-12">
                      <input type="text" name="price[]"  class="form-control bcz-quote-item-field bcz-quote-price" value="<?=$item['price']?>">
                    </div>
                  </div>
                </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Disc val</div>
                    <div class="col-xs-12">
                      <input type="text" name="discount[]" class="form-control bcz-quote-item-field bcz-quote-discount" value="<?=$item['discount']?>" data-type="number">
                    </div>
                  </div>
                </div>
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Disc %</div>
                    <div class="col-xs-12">
                      <input type="text" name="dis_percent[]" class="form-control bcz-quote-item-field bcz-quote-discount1" value="<?=$item['dis_percent']?>" data-type="number">
                    </div>
                  </div>
                </div>
                
                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">                  
                  <div class="form-group m-b-none">
                     <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Tax Type</div>
                    <div class="btn-group col-xs-12">
                
                      <select name="tax_type[]" class="select2-option col-xs-12 no-padder ">
                          <option value="">No</option>
                        <?php foreach($fields as $tax_name) { if ($tax_name->tax_name) { ?>
                          <option value="<?=$tax_name->tax_name?>" <?php if($tax_name->tax_name==$item['tax_type']){ ?> selected="selected" <?php } ?> ><?=$tax_name->tax_name?></option>
                        <?php } } ?>
                      </select>
                    </div>
                    
                  </div>
                </div>
                
                                <div class="col-sm-1 text-center padder-r-mini no-padder-l m-t-mini">                  
                  <div class="form-group m-b-none">
                     <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Tax %</div>
                    <div class="btn-group col-xs-12">
                      <select name="vat[]" class="select2-option col-xs-12 no-padder bcz-quote-vat">
                        <option value="0">0</option>
                        <?php foreach($fields as $tax) { if ($tax->tax) { ?>
                          <option value="<?=$tax->tax?>" <? if ($tax->tax == $item['vat']) { ?>selected="selected"<?php } ?>><?=$tax->tax?></option>
                        <?php } } ?>
                      </select>
                    </div>
                    
                  </div>
                </div>
                
                
                <div class="col-sm-2 text-center padder-r-mini no-padder-l m-t-mini ">
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Amount<?=$this->mandatoryFieldIndicator?></div>
                    <div class="col-xs-12">
                      <input type="text" name="amount[]" class="form-control bcz-quote-item-field bcz-quote-amount" readonly="readonly" value="<?=$item['amount']?>">
                    </div>
                  </div>
                </div>
              </div>
              <div class="row m-t-mini padder bcz-quote-item">
                <div class="col-sm-3 text-center padder-r-mini no-padder-l">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12 text-left m-t-small m-b-mini h5 visible-xs">Summary</div>
                    <div class="col-xs-12">
                      <textarea name="desc[]" placeholder="Summary" rows="2" class="form-control col-xs-12 bcz-quote-item-field"><?=$item['desc']?></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>

              <?php if (!$order->item5_id) { ?>
              <div class="row m-t padder">
                <div class="col-xs-3 padder-r-mini no-padder-l">                  
                  <div class="form-group m-b-none">
                    <div class="col-xs-12">
                      <a href="#" id="add_quote_item" class="btn btn-default btn-xs">Add Item</a>
                    </div>
                  </div>
                </div>
              </div>
              <?php } ?>

              <div class="row m-t padder">    
                <div class="col-sm-4 pull-right no-padder">
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Frieght:</label>
                    <div class="col-sm-7">
                      <input type="text" name="frieght" class="form-control bcz-quote-items-frieght" value="<?=$order->frieght?>" data-type="number">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Installation:</label>
                    <div class="col-sm-7">
                      <input type="text" name="install" class="form-control bcz-quote-items-install" value="<?=$order->install?>" data-type="number">
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-lg-3 control-label">Total:<?=$this->mandatoryFieldIndicator?></label>
                    <div class="col-sm-7">
                      <input type="text" name="total" class="form-control bcz-quote-items-total" data-required="true" readonly="readonly" value="<?=$order->total?>">
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
        <button type="submit" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>

  <!-- Dummy HTML Block for Quote Item -->
  <div class="hide" id="bcz_quote_dummy_item">
    <?php $this->load->view('SingleItemFieldsView', array('fields' => $fields, 'products' => $products)); ?>
  </div>

</div>