<?php if ($topLineFlag) { ?><div class="line pull-in"></div><?php } ?>
<article class="media">
  <div class="media-body">
  	<div class="pull-left"><img src="<?=$note->profile_pic?($note->fullPicPath ? '' : base_url().$this->imagesPath).$note->profile_pic:(base_url().'assets/img/default-profile-pic.png')?>" width="40" /></div>
    <p class="h5" style="margin-left: 50px;"><?=$note->note?></p>
    <div class="block" style="margin-left: 50px;">Written By <span class="text-danger"><strong><?=ucfirst($note->name?$note->name:$note->username)?></strong></span> On <span class="text-danger"><?=convertDateTime($note->note_create_date)?></span> At <span class="text-danger"><?=convertDateTime($note->note_create_date, 'H:i:s')?></span></div>
  </div>
</article>
<?php if ($bottomLineFlag) { ?><div class="line pull-in"></div><?php } ?>