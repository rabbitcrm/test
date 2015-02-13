<!-- .scrollable -->
  <section class="panel"> <header class="panel-heading">
   <ul class="nav nav-tabs nav-justified">
   <li class="body_home"><a href="#overdue" class="body_home" data-toggle="tab">Overdue Tasks</a></li> 
    <li class="body_home"><a href="#completed" class="body_home" data-toggle="tab">Completed Tasks</a></li> 
    <li class="active body_home"><a href="#today" class="body_home" data-toggle="tab">Today's Task</a></li>
    <li class="body_home"><a class="body_home" href="#Upcoming" data-toggle="tab">Upcoming Tasks</a></li>
    </ul> 
    </header> 
    <div class="panel-body"> 
    	<div class="tab-content">
        
        	<div class="tab-pane" id="overdue"><?php if ($overdue_tasks[0]) { foreach ($overdue_tasks as $nid => $overdue_task) { ?>
     <table >
      <article class="media">
     
     <tr>
        <div class="media-body">
        
         <td class="task_tital">
         
          <a href="<?=base_url().$overdue_task->item_type?>s/details/<?=$overdue_task->task_id?>" class="h5">
		 <?php $string = strip_tags($overdue_task->task_name);

if (strlen($string) >150) {
    // truncate string
    $stringCut = substr($string, 0, 150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}

		  ?>
		  <?=$string;?></a>
         
          
         <?php /*?> <small class="block m-b-none"><?=$complet_task->task_name?></small><?php */?>
         </td>
         
          <td class="task_tital"><?= $overdue_task->associate_name ?></td>
          <?php $company_name = strip_tags($overdue_task->company_name);
if (strlen($company_name) > 150) {
    // truncate string
    $stringCut = substr($company_name, 0,150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $company_name = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}
		  ?>
         <td class="task_company"><small class="block m-b-none"><?=$overdue_task->user_name_to?></small></td>
         
         <td class="task_priority"><small class="block m-b-none"><?=$overdue_task->priority?></small></td>
        
        <td class="task_date">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=convertDateTime($overdue_task->due_date, 'd')?></strong><br>
            <small class="label bg-light"><?=convertDateTime($overdue_task->due_date, 'M')?> <?=convertDateTime($overdue_task->due_date, 'y')?></small>
          </div>
         </td>
        
          
        </div>
        </tr>
      </article>
      </table>
      <?php if ($nid < (count($complet_task) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } ?><?php } else { ?>
      <p class="bcz-no-data-msg h5">No Overdue Tasks.</p>
    <?php } ?></div>
      
        
       
        	<div class="tab-pane" id="completed"><?php if ($complet_tasks[0]) { foreach ($complet_tasks as $nid => $complet_task) { ?>
     <table >
      <article class="media">
     
     <tr>
        <div class="media-body">
        
         <td class="task_tital">
         
          <a href="<?=base_url().$complet_task->item_type?>s/details/<?=$complet_task->task_id?>" class="h5">
		 <?php $string = strip_tags($complet_task->task_name);

if (strlen($string) >150) {
    // truncate string
    $stringCut = substr($string, 0, 150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}

		  ?>
		  <?=$string;?></a>
         
          
         <?php /*?> <small class="block m-b-none"><?=$complet_task->task_name?></small><?php */?>
         </td>
         
          <td class="task_tital"><?= $complet_task->associate_name ?></td>
          <?php $company_name = strip_tags($complet_task->company_name);
if (strlen($company_name) > 150) {
    // truncate string
    $stringCut = substr($company_name, 0,150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $company_name = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}
		  ?>
         <td class="task_company"><small class="block m-b-none"><?=$complet_task->user_name_to?></small></td>
         
         <td class="task_priority"><small class="block m-b-none"><?=$complet_task->priority?></small></td>
        
        <td class="task_date">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=convertDateTime($complet_task->due_date, 'd')?></strong><br>
            <small class="label bg-light"><?=convertDateTime($complet_task->due_date, 'M')?> <?=convertDateTime($complet_task->due_date, 'y')?></small>
          </div>
         </td>
        
          
        </div>
        </tr>
      </article>
      </table>
      <?php if ($nid < (count($complet_task) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } ?><?php } else { ?>
      <p class="bcz-no-data-msg h5">No Completed Tasks.</p>
    <?php } ?></div>
       
        
        
        
        
        
        
            <div class="tab-pane active" id="today"><?php if ($tasks[0]) { foreach ($tasks as $nid => $task) { ?>
      <table >
      <article class="media">
     
     <tr>
        <div class="media-body">
        
         <td class="task_tital">
         
          <a href="<?=base_url().$task->item_type?>s/details/<?=$task->task_id?>" class="h5">
		 <?php $string = strip_tags($task->task_name);

if (strlen($string) >150) {
    // truncate string
    $stringCut = substr($string, 0, 150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}

		  ?>
		  <?=$string;?></a>
         <?php /*?> <small class="block m-b-none"><?=$task->task_name?></small><?php */?>
         </td>
           <td class="task_tital"><?= $task->associate_name ?></td>
          <?php $company_name = strip_tags($task->company_name);
if (strlen($company_name) > 150) {
    // truncate string
    $stringCut = substr($company_name, 0,150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $company_name = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}
		  ?>
         <td class="task_company"><small class="block m-b-none"><?=$task->user_name_to?></small></td>
         
         <td class="task_priority"><small class="block m-b-none"><?=$task->priority?></small></td>
        
        <td class="task_date">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=convertDateTime($task->due_date, 'd')?></strong><br>
            <small class="label bg-light"><?=convertDateTime($task->due_date, 'M')?> <?=convertDateTime($task->due_date, 'y')?></small>
          </div>
         </td>
        
          
        </div>
        </tr>
      </article>
      </table>
      <?php if ($nid < (count($tasks) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } ?><?php } else { ?>
      <p class="bcz-no-data-msg h5">No Today's Task.</p>
    <?php } ?>    </div> 
    
    
    
    
    
    
            <div class="tab-pane" id="Upcoming"> <?php if ($coming_tasks[0]) { foreach ($coming_tasks as $nid => $task) { ?>
     <table >
      <article class="media">
     
     <tr>
        <div class="media-body">
        
         <td class="task_tital">
         
          <a href="<?=base_url().$task->item_type?>s/details/<?=$task->task_id?>" class="h5">
		 <?php $string = strip_tags($task->task_name);

if (strlen($string) >150) {
    // truncate string
    $stringCut = substr($string, 0, 150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $string = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}

		  ?>
		  <?=$string;?></a>
          
         <?php /*?> <small class="block m-b-none"><?=$task->task_name?></small><?php */?>
         </td>
         <td class="task_tital"><?= $task->associate_name ?></td>
          <?php $company_name = strip_tags($task->company_name);
if (strlen($company_name) > 150) {
    // truncate string
    $stringCut = substr($company_name, 0,150);
    // make sure it ends in a word so assassinate doesn't become ass...
    $company_name = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
}
		  ?>
         <td class="task_company"><small class="block m-b-none"><?=$task->user_name_to?></small></td>
         
         <td class="task_priority"><small class="block m-b-none"><?=$task->priority?></small></td>
        
        <td class="task_date">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=convertDateTime($task->due_date, 'd')?></strong><br>
            <small class="label bg-light"><?=convertDateTime($task->due_date, 'M')?> <?=convertDateTime($task->due_date, 'y')?></small>
          </div>
         </td>
        
          
        </div>
        </tr>
      </article>
      </table>
      <?php if ($nid < (count($tasks) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } ?><?php } else { ?>
      <p class="bcz-no-data-msg h5">No Upcoming Tasks.</p>
    <?php } ?></div>
       </div>
    </div>
  </section>
    
<!-- / scrollable -->