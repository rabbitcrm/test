<form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>settings/updateuser">
  <div class="modal-dialog">
    <div class="modal-content"><!-- .modal-content -->
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
        <h4 class="modal-title">Edit user details</h4>
      </div>
      <div class="modal-body">
        <div class="alert ta-left alert-danger hide">
          <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
          <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
        </div>

        <div class="form-group">
          <label class="col-sm-4 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
          <div class="col-sm-6">
            <input type="text" name="name" class="form-control" data-required="true" value="<?=$user->name?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
          <div class="col-sm-6">
            <input type="email" name="user_email" placeholder="test@example.com" class="form-control" data-required="true" data-type="email" value="<?=$user->user_email?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Designation<?=$this->mandatoryFieldIndicator?></label>
          <div class="btn-group col-sm-6 m-b">
            <select name="user_designation" class="select2-option col-sm-12 no-padder" data-required="true">
              <option value=""><?=$this->chooseOption?></option>
              <?php foreach($fields as $designation) { if ($designation->designation) { ?>
                <option value="<?=$designation->designation?>" <? if ($designation->designation == $user->user_designation) { ?>selected="selected"<?php } ?>><?=$designation->designation?></option>
              <?php } } ?>
            </select>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-4 control-label">Report To</label>
          <div class="btn-group col-sm-6 m-b">
            <select name="report_to_id" class="select2-option col-sm-12 no-padder">
              <option value=""><?=$this->chooseOption?></option>
              <?php foreach($users as $reporter) { ?>
                <?php if ($reporter->user_id != $user->user_id) { ?>
                <option value="<?=$reporter->user_id?>" <? if ($reporter->user_id == $user->report_to_id) { ?>selected="selected"<?php } ?>><?=$reporter->name?></option>
                <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
        <button id="update_user" type="submit" class="btn btn-sm btn-primary">Save</button>
        <input type="hidden" name="user_id" class="form-control" data-required="true" value="<?=$user->user_id?>">
      </div>
    </div><!-- /.modal-content -->
  </div>
</form>