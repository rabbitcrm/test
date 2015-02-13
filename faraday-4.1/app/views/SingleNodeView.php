<ul>
<?php foreach ($node as $name => $item) { $itemType = is_array($item) ? 'folder' : 'file'; ?>
  <li>
    <span class='<?=$itemType?> <?=($itemType == "file") ? "h5" : "h4"?>'>
    	&nbsp;<?=($itemType == 'folder') ? $name : $item?>
		
		<?php if ($itemType == 'file') { ?>
		    <a target="_blank" href="<?=base_url()?>files/view?type=doc&name=<?=$item?><?=($path?'&path='.$path:'')?>" class="m-l"><i class="icon-eye-open icon-large"></i></a>
		    <a target="_blank" href="<?=base_url()?>files/download?type=doc&name=<?=$item?><?=($path?'&path='.$path:'')?>" class=" m-l-mini"><i class="icon-download-alt icon-large"></i></a>
	    <?php } ?>
    </span>
    <?php if ($itemType == 'folder') $this->load->view('SingleNodeView', array('node' => $item, "path" => $path.($path?'/':'').$name)); ?>
  </li>
<?php } ?>
</ul>