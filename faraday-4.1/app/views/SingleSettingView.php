<div class="list-group-item" data-id="<?=$id?>" data-order="<?=$order?>" <?php if (isset($probability)) { ?>data-probability="<?=$probability?>%"<?php } ?>>
	<!--            
	<span class="bcz-btn-up-down">
	  <i class="icon-sort-up icon-large"></i>
	  <i class="icon-sort-down icon-large"></i>
	</span>
	-->
	<span class="bcz-data h5"><?=$text?></span>
	<?php if (!$noDelete) { ?><span class="pull-right">
    <?php if(($id!='24') && ($id!='25')) { ?>
    <a id="<?=$id; ?>" href="#delete_setting_confirmation_modal" data-toggle="modal" data-action="delete"><i class="icon-remove-sign icon-large"></i></a>
    
	<?php }	else { ?> <a href="#not_delete_setting_confirmation_modal" data-toggle="modal" data-action="not_delete"><i class="icon-remove-sign icon-large"></i></a>
     <?php  }  ?>
    
    </span><?php } ?>
	<span class="pull-right m-r-mini"><a href="#edit_setting_modal" data-toggle="modal" data-action="edit"><i class="icon-edit-sign icon-large"></i></a></span>
	<span class="pull-right m-r-mini bcz-arrow-up"><a href="#moveup" data-action="moveup"><i class="icon-sort-up icon-large"></i></a></span>
	<span class="pull-right m-r-mini bcz-arrow-down"><a href="#movedown" data-action="movedown"><i class="icon-sort-down icon-large"></i></a></span>
</div>