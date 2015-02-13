<div class="clearfix">
  <h3>Add Task</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>tasks/submit">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
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
        </div>
      </section>
    </div>

    <div class="col-sm-12">
      <div class="form-group m-b-small">
        <?php if (isset($_SESSION['taskInfo'])) { ?>
          <input type="hidden" name="associate_to" value="<?=$_SESSION['taskInfo']['associate_to']?>" />
          <input type="hidden" name="associate_id" value="<?=$_SESSION['taskInfo']['associate_id']?>" />
        <?php } ?>
        <button type="submit" class="btn btn-primary m-l">Create</button>
      </div>
    </div>
  </form>
</div>