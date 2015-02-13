<?php if ($topLineFlag) { ?><div class="line pull-in"></div><?php } ?>
<article class="media">
  <div class="media-body">
  	<?php $extension = end(explode('.', $doc->filename)); $filetype = ($extension == 'jpg' || $extension == 'jpeg') ? 'jpeg' : (($extension == 'gif') ? 'gif' : ($extension == 'txt') ? 'txt' : 'file'); ?>
  	<img class="pull-left m-r-mini" src="<?=base_url()?>assets/img/icons/<?=$filetype?>.png" width="14" height="16" />
    <div class="h4">
    	<?=$doc->filename?>
    	<button class="btn btn-inverse btn-xs pull-right delete-entity-doc bcz-confirm-operation" data-action="<?=base_url()?>files/deleteEntityDoc" data-id="<?=$doc->file_id?>" data-name="<?=$doc->filename?>">Delete</button>
    	<a href="<?=base_url()?>files/download?type=image&name=<?=$doc->filename?>" class="btn btn-inverse btn-xs pull-right m-r-mini">Download</a>
   	</div>
    <div class="block m-t-mini">Uploaded By <span class="text-danger"><strong><?=ucfirst($doc->name)?></strong></span> On <span class="text-danger"><?=convertDateTime($doc->file_create_date)?></span></div>
  </div>
</article>
<?php if ($bottomLineFlag) { ?><div class="line pull-in"></div><?php } ?>