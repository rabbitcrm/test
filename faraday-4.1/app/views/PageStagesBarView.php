<section class="panel contentli"> 
<?php if(count($stages)>=6) { $smail='smail'; } else { $smail='';  }   ?>
  <div class="wizard clearfix <?=$smail;?>">
   <div class="actions" data-id="<?=$pageId?>" style="float:left; margin:0;">
     <?php $h=$m=0; 
	  foreach ($stages as $sid => $stage) { 
	  $h++; if ($stage == $pageStage) { $m=$h;  }  } ?>
       <button type="button" style="padding: 11px 11px;margin-right: 38px;" class="btn btn-white btn-xs prev-btn <?=($m == 1)?'hide':''?>"><i class="icon-chevron-left icon-large"></i></button>
</div>
<div>
    <ul class="steps">
      <?php 
	  foreach ($stages as $sid => $stage) { 
	  $g++;   ?>
        <li data-li="<?=$g;?>" <?php if($g=='1') { echo 'style="padding-left:20px;"'; } ?> data-stage="<?=$sid?>" <?php if ($stage == $pageStage) { $yk=$yk; ?>class="active"<?php } ?>><span class="badge <?php if ($stage == $pageStage) { ?>badge-info<?php } ?>"><?=$g?></span><?=$stage?></li>
        
      <?php  if ($stage == $pageStage) $activeStageIindex = $g; } ?><input type="hidden" id="stage_panel" value="<?=$g;?>" />
      <input type="hidden" id="stage_active" value="<?=$yk;?>" />
    </ul>
    </div>
    <div class="actions" data-id="<?=$pageId?>" style=" margin:0;">
<?php /*?>      <button type="button" style="" class="btn btn-white btn-xs prev-btn <?=($activeStageIindex == 1)?'hide':''?>"><i class="icon-chevron-left icon-large"></i></button>
<?php */?>      <button type="button" style="padding: 11px 11px;" class="btn btn-white btn-xs next-btn <?=($activeStageIindex == (count($stages) ))?'hide':''?>"><i class="icon-chevron-right icon-large"></i></button>
    </div>
  </div>
</section>

