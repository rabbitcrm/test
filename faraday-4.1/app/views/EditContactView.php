<div class="clearfix">
  <h3>Edit Contact</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>contacts/update/<?=$contact->contact_id?>">
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
                      <span class="dropdown-label col-xs-11 text-left"><?=$contact->title?$contact->title:'Mr.'?></span>
                      <span class="caret"></span>
                    </button> 
                    <ul class="dropdown-menu dropdown-select col-xs-12">
                      <?php foreach($fields as $title) { if ($title->name_title) { ?>
                        <li><a href="#"><input type="radio" name="title" value="<?=$title->name_title?>" <? if ($title->name_title == $contact->title) { ?>checked="checked"<?php } ?>><?=$title->name_title?></a></li>
                      <?php } } ?>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-4 m-t-mini">
                  <input type="text" name="first_name" placeholder="First Name" id="lead_fname" class="form-control" value="<?=$contact->first_name?>">
                </div>
                <div class="col-sm-5 m-t-mini">
                  <input type="text" name="last_name" placeholder="Last Name" id="lead_lname" class="form-control" value="<?=$contact->last_name?>">
                </div>
                 <div id="error"></div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="company_id" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($companies as $company) { ?>
                      <option value="<?=$company->company_id?>" <? if ($company->company_id == $contact->company_id) { ?>selected="selected"<?php } ?>><?=$company->company_name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Phone<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="mobile" class="form-control" data-required="true" value="<?=$contact->mobile?>" data-type="number">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="email" name="email" placeholder="test@example.com" class="form-control" data-required="true" data-type="email" value="<?=$contact->email?>">
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
                  <label class="col-lg-3 control-label">Designation</label>
                  <div class="col-lg-9">
                    <input type="text" name="designation" class="form-control" value="<?=$contact->designation?>">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Alternate Email</label>
                  <div class="col-lg-9">
                    <input type="email" name="alt_email" placeholder="test@example.com" class="form-control" data-type="email" value="<?=$contact->alt_email?>">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Phone1</label>
                  <div class="col-lg-9">
                    <input type="text" name="phone" class="form-control" value="<?=$contact->phone?>" data-type="number">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Address</label>
                  <div class="col-lg-9">
                    <textarea name="address" rows="3" class="form-control" data-trigger="keyup"><?=$contact->address?></textarea>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Address 1</label>
                  <div class="col-lg-9 no-padder">
                    <div class="col-sm-5 m-t-mini">
                      <input type="text" name="city" placeholder="City" class="form-control" value="<?=$contact->city?>">
                    </div>
                    <div class="col-sm-4 m-t-mini">
                      <input type="text" name="state" placeholder="State" class="form-control" value="<?=$contact->state?>">
                    </div>
                    <div class="col-sm-3 m-t-mini">
                      <input type="text" name="postal_code" placeholder="Zip" class="form-control" value="<?=$contact->postal_code?>" data-type="number">
                    </div>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Country</label>
                  <div class="col-lg-9">
                    <div class="btn-group col-xs-12 no-padder">
                      <select name="country" class="select2-option">
                        <option value=""><?=$this->chooseOption?></option>
                        <?php foreach($fields as $country) { if ($country->country) { ?>
                          <option value="<?=$country->country?>" <? if ($country->country == $contact->country) { ?>selected="selected"<?php } ?>><?=$country->country?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Summary</label>
                  <div class="col-lg-9">
                    <textarea name="con_description" rows="3" class="form-control" data-trigger="keyup"><?=$contact->con_description?></textarea>
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
      <div class="form-group m-b-small">
        <button type="submit" id="create_lead" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>
</div>