<?php $docItems = array_values($docData); $isDir = is_array($docItems[0]); ?>
<?php if ($isDir || $docData['name']) { ?>
  <div class="list-item <?=$isDir?'directory':'file'?>">
    <div class="col-xs-10 m-b-mini m-t-mini h5">
      <?php
        if ($isDir) {
          $iconname = 'folder-icon.png';
        } else {
          $extension = strtolower(end(explode('.', $docData['name'])));
          if ($extension == 'xlsx') $extension = 'xls'; 
          if ($extension == 'docx') $extension = 'doc'; 
          $iconname = in_array($extension, array('jpeg', 'jpg', 'png', 'gif', 'bmp')) ? '' : $extension.'-icon.png';
        }
      ?>
      <img class="m-r-mini pull-left" src="<?=base_url().($iconname ? 'assets/img/'.$iconname : $this->docsPath.($path?$path.'/':'').$docData['name'])?>" width="32" />
      <div class="m-t-mini">
        <?=$isDir?$docKey:$docData['name']?>
        <?php if (!$isDir) { ?>
        <div class="pull-right doc-actions">
          <span class="m-r-small"><a target="_blank" href="<?=base_url()?>files/view?type=doc&name=<?=$docData['name']?><?=($path?'&path='.$path:'')?>"><i class="icon-eye-open icon m-r-mini"></i>View</a></span>
          <span class="m-r-small"><a class="bcz-confirm-operation bcz-delete-doc-btn" href="#" data-url="<?=base_url()?>documents/deleteDoc?name=<?=$docData['name']?><?=($path?'&path='.$path:'')?>"><i class="icon-remove icon m-r-mini"></i>Delete</a></span>
          <span><a href="<?=base_url()?>files/download?type=doc&name=<?=$docData['name']?><?=($path?'&path='.$path:'')?>"><i class="icon-download-alt icon m-r-mini"></i>Download</a></span>
        </div>
        <?php } ?>
      </div>
    </div>
    <?php if (!$isDir) { ?><div class="col-xs-2 m-b-mini m-t-small h5"><?=$docData['size']?></div><?php } ?>
  </div>

  <?php if ($isDir) { ?>
    <div class="directory-files hidden">
      <?php foreach ($docData as $nid => $node) { ?>
        <?php $this->load->view('SingleDocInfoView', array('docData' => $node, 'docKey' => $nid, "path" => $path.($path?'/':'').$docKey)); ?>
      <?php } ?>
    </div>
  <?php } ?>
<?php } ?>