<!-- .modal -->
<div id="create_task_modal" class="modal fade">
  <form class="form-horizontal bcz-add-item-entity-form" method="post" data-validate="parsley" action="<?=base_url()?>tasks/submit">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Add a new task</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Task<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <input type="text" name="task_name" class="form-control" data-required="true">
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Type<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="type" class="select2-option" data-required="true">
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($fields as $task_type) { if ($task_type->task_type) { ?>
                    <option value="<?=$task_type->no?>"><?=$task_type->task_type?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Assigned to<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="assign_to" class="select2-option" data-required="true">
                  <?php foreach($users as $user) { ?>
                    <option value="<?=$user->user_id?>" <?php if ($user->user_id == $this->user->user_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Priority<?=$this->mandatoryFieldIndicator?></label>
            <div class="col-lg-9">
              <div class="btn-group col-xs-12 no-padder">
                <select name="priority" class="select2-option" data-required="true">
                  <option value=""><?=$this->chooseOption?></option>
                  <?php foreach($fields as $priority) { if ($priority->priority) { ?>
                    <option value="<?=$priority->priority?>"><?=$priority->priority?></option>
                  <?php } } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Due Date</label>
            <div class="col-lg-9">
              <input type="text" name="due_date" class="form-control datepicker" value="<?=$this->today?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
            </div>
          </div>
          
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Due Time</label>
              <div class="col-lg-9">
                <input type="text" name="due_time" id="timepicker" value="10:00 AM"  >
              </div>
            </div>
            
          <div class="form-group m-b-small">
            <label class="col-lg-3 control-label">Summary</label>
            <div class="col-lg-9">
              <textarea name="description" rows="3" class="form-control"><?=$task->description?></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="associate_to" value="" />
          <input type="hidden" name="associate_id" value="" />
          <input type="hidden" name="modal_flag" value="1" />
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="add_task" type="submit" class="btn btn-sm btn-primary">Add</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->