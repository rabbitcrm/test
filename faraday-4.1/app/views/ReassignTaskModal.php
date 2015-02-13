<!-- .modal -->
<div id="reassign_task_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>tasks/reassign">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Reassign Task</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body text-center">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>

              <label class="col-sm-5 control-label m-t-mini text-right">Reassign to:</label>
              <div class="btn-group col-sm-4">
                <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle col-sm-12">
                  <span class="dropdown-label col-sm-11 text-left no-padder"><?=$task->assignee->name?$task->assignee->name:($this->user->name?$this->user->name:$this->user->username)?></span>
                  <span class="caret"></span>
                </button> 
                <ul class="dropdown-menu dropdown-select col-sm-12 text-left">
                  <?php foreach($users as $user) { if ($task->assign_to != $user->user_id) { ?>
                    <li><a href="#"><input type="radio" name="assign_to" value="<?=$user->user_id?>" <?php if ($user->user_id == $this->user->user_id) { ?> checked="checked"<?php } ?>><?=$user->name?></a></li>
                  <?php } } ?>
                </ul>
              </div>
              <input type="hidden" name="task_id" value="<?=$task->task_id?>">
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="button" id="reassign_task" class="btn btn-sm btn-primary bcz-submit-btn" data-loading-text="Reassigning...">Reassign</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->