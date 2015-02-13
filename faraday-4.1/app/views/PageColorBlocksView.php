<div class="row padder bcz-colored-boxes">
  <?php foreach ($colorBlocks as $colorBlock) { ?>
  <div class="col-sm-<?=$colorBlock['cols']?$colorBlock['cols']:3?> bg-<?=$colorBlock['color']?> padder-v text-center">
  <?php if(strlen($colorBlock['head'])<=16){ $bodyclass="h2"; }else if(strlen($colorBlock['head'])<=19){ $bodyclass="h3"; }else if(strlen($colorBlock['head'])<=26){ $bodyclass="bodyh"; }else if(strlen($colorBlock['head'])>=27){ $bodyclass="h4"; }$bodyclass="h2"; ?>
    <div class="<?=$bodyclass?>"><?=$colorBlock['head']?></div>
    
    
    <?php 
	$bodyclass="";
	if($colorBlock['body'])
	{
	if(strlen($colorBlock['body'])<=16){ $bodyclass="h2"; }else if(strlen($colorBlock['body'])<=19){ $bodyclass="h3"; }else if(strlen($colorBlock['body'])<=26){ $bodyclass="bodyh"; }else if(strlen($colorBlock['body'])>=27){ $bodyclass="h4"; } }else
	{
		$bodyclass="h2";
	}
	$bodyclass="h2";
	?>
    
    
    
    <div class="<?=$bodyclass?> <?=$colorBlock['bodyCls']?>">
    	<?php if ($colorBlock['link']) { ?><a title="<?=$colorBlock['body']?>" href="<?=base_url().$colorBlock['link']?>" class="text-white"><?php } ?>
    	<?=$colorBlock['body']?(($colorBlock['type'] == 'date')?convertDateTime($colorBlock['body']):$colorBlock['body']):$this->noDataChar?>
    	<?php if ($colorBlock['link']) { ?></a><?php } ?>
    </div>
  </div>
  <?php } ?>
</div>