<!-- .modal -->
<div id="add_user_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>settings/addUser">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Create a new user</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <div class="form-group">
            <label class="col-sm-4 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-sm-8">
              <input type="text" name="name" class="form-control" data-required="true">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-sm-8">
              <input type="email" name="user_email" placeholder="test@example.com" class="form-control" data-required="true" data-type="email">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">Designation<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-sm-8">
              <div class="btn-group col-xs-12 no-padder">
                <select name="user_designation" class="select2-option" data-required="true">
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($fields as $designation) { if ($designation->designation) { ?>
                    <option value="<?=$designation->designation?>"><?=$designation->designation?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-4 control-label">Report To</label>
            <div class="col-sm-8">
              <div class="btn-group col-xs-12 no-padder">
                <select name="report_to_id" class="select2-option">
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($users as $user) { ?>
                    <option data-role="<?=$user->user_designation?>" value="<?=$user->user_id?>" <? if ($user->user_id == $this->user->user_id) { ?>selected="selected"<?php } ?>><?=$user->name?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="add_user" type="submit" class="btn btn-sm btn-primary">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->