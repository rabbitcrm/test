<div class="clearfix">
  <h3>Add Contact</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>contacts/submit">
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
                      <span class="dropdown-label col-xs-11">Mr.</span>
                      <span class="caret"></span>
                    </button> 
                    <ul class="dropdown-menu dropdown-select col-xs-12">
                      <?php foreach($fields as $title) { if ($title->name_title) { ?>
                        <li><a href="#"><input type="radio" name="title" value="<?=$title->name_title?>"><?=$title->name_title?></a></li>
                      <?php } } ?>
                    </ul>
                  </div>
                </div>
                <div class="col-sm-4 m-t-mini">
                  <input type="text" name="first_name" placeholder="First Name" id="lead_fname" class="form-control">
                </div>
                <div class="col-sm-5 m-t-mini">
                  <input type="text" name="last_name" placeholder="Last Name" id="lead_lname" class="form-control">
                </div>
                <div id="error"></div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Account<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-11 no-padder">
                  <select name="company_id" class="select2-option" data-required="true" <? if ($_SESSION['contactCompany']) { ?>disabled="disabled"<?php } ?>>
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($companies as $company) { ?>
                      <option value="<?=$company->company_id?>" <? if ($company->company_id == $_SESSION['contactCompany']) { ?>selected="selected"<?php } ?>><?=$company->company_name?></option>
                    <?php } ?>
                  </select>
                <? if ($_SESSION['contactCompany']) { ?><input type="hidden" name="company_id" class="form-control" value="<?=$_SESSION['contactCompany']?>"><?php } ?>
                </div>              
                <div class="text-left pull-right"><a href="<?=base_url()?>contacts/addcompany" class="btn btn-inverse btn-xs"> <i class="icon-plus icon-large"></i> </a></div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Phone<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="mobile" class="form-control" data-required="true" data-type="number">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="email" name="email" placeholder="test@example.com" class="form-control" data-required="true" data-type="email">
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
                    <input type="text" name="designation" class="form-control">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Alternate Email</label>
                  <div class="col-lg-9">
                    <input type="email" name="alt_email" placeholder="test@example.com" class="form-control" data-type="email">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Phone1</label>
                  <div class="col-lg-9">
                    <input type="text" name="phone" class="form-control" data-type="number">
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Address</label>
                  <div class="col-lg-9">
                    <textarea name="address" rows="3" class="form-control" data-trigger="keyup"></textarea>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Address 1</label>
                  <div class="col-lg-9 no-padder">
                    <div class="col-sm-5">
                      <input type="text" name="city" placeholder="City" class="form-control">
                    </div>
                    <div class="col-sm-4">
                      <input type="text" name="state" placeholder="State" class="form-control">
                    </div>
                    <div class="col-sm-3">
                      <input type="text" name="postal_code" placeholder="Zip" class="form-control" data-type="number">
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
                          <option value="<?=$country->country?>" <? if (strtolower($country->country) == 'india') { ?>selected="selected"<?php } ?>><?=$country->country?></option>
                        <?php } } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="form-group m-b-small">
                  <label class="col-lg-3 control-label">Summary</label>
                  <div class="col-lg-9">
                    <textarea name="con_description" rows="3" class="form-control" data-trigger="keyup"></textarea>
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
        <?php if (isset($_SESSION['contactInfo'])) { ?>
          <input type="hidden" name="associate_to" value="<?=$_SESSION['contactInfo']['associate_to']?>" />
          <input type="hidden" name="associate_id" value="<?=$_SESSION['contactInfo']['associate_id']?>" />
        <?php } ?>
        <button type="submit" class="btn btn-primary m-l" id="create_lead">Create</button>
      </div>
    </div>
  </form>
</div>