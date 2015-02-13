<div class="clearfix">
  <h3>Edit Task</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>tasks/update/<?=$task->task_id?>">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Task<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <input type="text" name="task_name" class="form-control" data-required="true" value="<?=$task->task_name?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Type<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="type" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $task_type) { if ($task_type->task_type) { ?>
                      <option value="<?=$task_type->no?>"<? if ($task_type->no == $task->type) { ?>selected="selected"<?php } ?>><?=$task_type->task_type?></option>
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
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $task->assign_to) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
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
                      <option value="<?=$priority->priority?>"<? if ($priority->priority == $task->priority) { ?>selected="selected"<?php } ?>><?=$priority->priority?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>            
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Status<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="status" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $task_status) { if ($task_status->task_status) { ?>
                      <option value="<?=$task_status->no?>" <?php if ($task_status->no == $task->status) { ?> selected="selected"<?php } ?>><?=$task_status->task_status?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Due Date</label>
              <div class="col-lg-9">
                <input type="text" name="due_date" class="form-control datepicker" value="<?=($task->due_date && $task->due_date != '0000-00-00')?date('d-m-Y', strtotime($task->due_date)):''?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Due Time</label>
              <div class="col-lg-9">
                <input type="text" name="due_time" id="timepicker" value="<?=$task->due_time?>"  >
              </div>
            </div>
            <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Summary</label>
              <div class="col-lg-9">
                <textarea name="description" rows="3" class="form-control"><?=$task->description?></textarea>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>

    <div class="col-sm-12">
      <div class="form-group m-b-small">
        <button type="submit" class="btn btn-primary m-l">Update</button>
      </div>
    </div>
  </form>
</div>