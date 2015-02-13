<div class="clearfix">
  <h3>Profile</h3>
</div>

<div class="row">
  <div class="col-sm-12">      
    <section class="panel">
    	<div class="panel-body">
        <div class="col-sm-6">
          <? $this->load->view('MessagesView', array(messages => $messages)) ?>

          <form class="form-horizontal" method="post" autocomplete="off" data-validate="parsley" action="<?=base_url()?>profile" accept-charset="utf-8" enctype="multipart/form-data">
            <div class="form-group">
              <label class="col-lg-3 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="name" data-required="true" class="form-control" value="<?=$this->user->name?>">
              </div>
            </div>
            <?php /*?><div class="form-group">
              <label class="col-lg-3 control-label">Username<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="username" data-required="true" class="form-control" value="<?=$this->user->username?>" <?php if ($this->isAdmin) { ?>disabled="disabled"<?php } ?>>
              </div>
            </div><?php */?>
            <div class="form-group">
              <label class="col-lg-3 control-label">Designation<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select class="select2-option" data-required="true" disabled="disabled">
                    <?php foreach($fields as $designation) { if ($designation->designation) { ?>
                      <option value="<?=$designation->designation?>" <?php if ($designation->designation == $this->user->user_designation) { ?> selected="selected"<?php } ?>><?=$designation->designation?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="user_email" data-required="true" class="form-control" value="<?=$this->user->user_email?>">
              </div>
            </div>
            <?php if ($this->user->report_to_id) { ?>
            <div class="form-group">
              <label class="col-lg-3 control-label">Report to</label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="report_to_id" class="select2-option" <?php if ($this->isAdmin) { ?>disabled="disabled"<?php } ?>>
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $this->user->report_to_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <?php } ?>
            <div class="form-group">
              <label class="col-lg-3 control-label">Profile picture</label>
              <div class="col-lg-9 media">
                <div class="bg-light pull-left text-center media-large thumb-large">
                  <?php if ($this->user->profile_pic) { ?>
                    <img id="imgprvw" src="<?=$this->user->profile_pic?>">
                  <?php } else { ?>
                    <i class="icon-user inline icon-light icon-3x m-t-large m-b-large"></i>
                  <?php } ?>
                </div>
                <div class="media-body">
                  <input type="file" name="profile_pic" title="Choose file" onchange="showimagepreview(this)" id="filUpload" class="btn btn-sm btn-info m-b-small"><br>
                </div>
              </div>
            </div>

            <!-- .accordion -->
            <div class="panel-group m-b" id="accordion2">
              <div class="panel">
                <div class="panel-heading fieldset-head">
                  <a class="accordion-toggle h5" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                    <strong>Change Password</strong>
                    <span class="caret pull-right"></span>
                  </a>
                </div>
                <div id="collapseOne" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div class="form-group">
                      <label class="col-lg-4 control-label">Current Password</label>
                      <div class="col-lg-8">
                        <input type="password"  autocomplete="off" name="curr_password" class="form-control" value="" >
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-lg-4 control-label">New Password</label>
                      <div class="col-lg-8">
                        <input type="password" name="password" class="form-control">
                      </div>
                    </div>
                    <div class="form-group m-b-none">
                      <label class="col-lg-4 control-label">Confirm Password</label>
                      <div class="col-lg-8">
                        <input type="password" name="conf_password" class="form-control">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-group m-b" id="accordion3">
              <div class="panel">
                <div class="panel-heading fieldset-head">
                  <a class="accordion-toggle h5" data-toggle="collapse" data-parent="#accordion3" href="#collapseTwo">
                    <strong>Mail Signature</strong>
                    <span class="caret pull-right"></span>
                  </a>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse">
                  <div class="panel-body text-small">
                    <div class="form-group m-b-none">
                      <div class="col-lg-12">
                        <textarea name="mail_signature" rows="3" class="form-control" data-trigger="keyup"><?=$this->user->mail_signature?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="panel-group m-b" id="accordion4">
              <div class="panel">
                <div class="panel-heading fieldset-head">
                  <a class="accordion-toggle h5" data-toggle="collapse" data-parent="#accordion4" href="#collapseFour">
                    <strong>User Settings</strong>
                    <span class="caret pull-right"></span>
                  </a>
                </div>
                <div id="collapseFour" class="panel-collapse collapse">
                  <div class="panel-body">
                    <div class="form-group m-b-small">
                      <label class="col-lg-3 control-label">Date Format</label>
                      <div class="col-lg-9" id="sele_box">
                        <div class="btn-group col-xs-12 no-padder">
                          <select name="set_date_format" class="form-control" id="set_date_format">
                            <option value=""><?=$this->chooseOption?></option>
                            <option value="m/d/Y" <?php if ($this->user->settings->date_format == 'm/d/Y') { ?>selected="selected"<?php } ?>>MM/DD/YYYY</option>
                            <option value="d/m/Y" <?php if ($this->user->settings->date_format == 'd/m/Y') { ?>selected="selected"<?php } ?>>DD/MM/YYYY</option>
                            <option value="d M Y" <?php if ($this->user->settings->date_format == 'd M Y') { ?>selected="selected"<?php } ?>>DD MMM YYYY</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group m-b-small">
                      <label class="col-lg-3 control-label">Currency</label>
                      <div class="col-lg-9">
                        <div class="btn-group col-xs-12 no-padder">
                          <select name="set_currency" class="select2-option">
                            <option value=""><?=$this->chooseOption?></option>
                            <?php foreach($fields as $currency) { if ($currency->currency) { ?>
                              <option value="<?=$currency->currency?>" <?php if ($this->user->settings->currency == $currency->currency) { ?>selected="selected"<?php } ?>><?=$currency->currency?></option>
                            <?php } } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="form-group m-b-small">
                      <label class="col-lg-3 control-label">Timezone</label>
                      <div class="col-lg-9">
                        <div class="btn-group col-xs-12 no-padder">
                          <select name="set_timezone" class="select2-option">
                            <option value=""><?=$this->chooseOption?></option>
                            <?php foreach($timezones as $zoneKey => $zoneLable) { ?>
                              <option value="<?=$zoneKey?>" <?php if ($this->user->settings->timezone == $zoneKey) { ?>selected="selected"<?php } ?>><?=$zoneLable?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="form-group">
              <button type="submit" class="btn btn-primary m-l">Save</button>
            </div>
          </form>
        </div>
    	</div>
    </section>
  </div>
</div>
