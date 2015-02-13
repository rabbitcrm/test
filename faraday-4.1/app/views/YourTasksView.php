<!-- task -->
<section class="panel" id="your_tasks">
  <header class="panel-heading h5">Tasks</header>
  <ul class="list-group">
    <?php if ($tasks[0]) { foreach ($tasks as $tid => $task) { ?>
      <li class="list-group-item <?=($tid%2)?'bg':''?>" data-toggle="class:active" data-target="#todo-<?=$tid+1?>">
        <div class="media">
          <span class="pull-left thumb-small m-t-mini">
            <i class="icon-check icon-xlarge text-default"></i>
          </span>
          <div id="todo-<?=$tid+1?>" class="pull-right text-primary m-t-small">
            <i class="icon-circle icon-large text text-default"></i>
            <i class="icon-ok-sign icon-large text-active text-primary"></i>
          </div>
          <div class="media-body">
            <div><a href="<?=base_url()?>tasks/details/<?=$task->task_id?>" class="h5"><?=$task->task_name?></a></div>
            <small class="text-muted"><?=$task->description?></small>
          </div>
        </div>
      </li>
    <?php } } else { ?>
      <li class="list-group-item bcz-no-data-msg h5">No tasks found.</li>
    <?php } ?>
  </ul>
</section>
<!-- / task-->