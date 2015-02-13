<div class="pull-right m-b-mini">
  <?php if ($pageActions[0]) { ?>
    <div class="btn-group m-t">
      <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle m-r-mini">
        <span class="dropdown-label" data-placeholder="Action">Actions</span>
        <span class="caret"></span>
      </button>

      <ul class="dropdown-menu page-actions <?=$pageType?>-actions" data-pageid="<?=$pageId?>">
        <?php foreach ($pageActions as $pageAction) { ?>
          <li><a title="<?=$pageAction['label']?>" <?php if($pageAction['href']!="") { echo "href='".base_url().$pageAction['href']."'"; }else { ?>href="#<?=($pageAction['modalId']?$pageAction['modalId']:'')?>" <?php } ?> <?php if ($pageAction['redirectPath']) { ?>data-redirect-url="<?=base_url().$pageAction['redirectPath']?>"<?php } ?> class="<?=$pageAction['class']?>" <?php if ($pageAction['target']) { ?>target="<?=$pageAction['target']?>"<?php } ?>><i class="<?=$pageAction['icon']?>"></i><?=$pageAction['label']?></a></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>

  <?php if ($prevId || $nextId) { ?>
    <div class="btn-group actions m-t m-r-small">
      <?php if ($prevId!=0) { ?><a href="<?=base_url().$controller?>/details/<?=$prevId?>" class="btn btn-white btn-sm"><i class="icon-chevron-left"></i> Prev</a><?php } ?>
      <?php if ($nextId!=0) { ?><a href="<?=base_url().$controller?>/details/<?=$nextId?>" class="btn btn-white btn-sm">Next <i class="icon-chevron-right"></i></a><?php } ?>
    </div>
  <?php } ?>
</div>