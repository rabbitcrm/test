<?php foreach ($borderBlocks as $bid => $borderBlock) { ?>
  <div class="col-sm-<?=$borderBlock['cols']?$borderBlock['cols']:6?> text-center padder-r-mini no-padder-l m-t-mini <?=$borderBlock['class']?$borderBlock['class']:''?>">
    <div class="panel m-b-none">
      <div class="panel-body">
        <div class="h4"><?=$borderBlock['head']?></div>
        <div class="h4 text-<?=$borderBlock['color']?$borderBlock['color']:(($bid%2)?'warning':'danger')?> <?=$borderBlock['bodyCls']?>">
          <?=$borderBlock['body']?(($borderBlock['type'] == 'date')?convertDateTime($borderBlock['body']):$borderBlock['body']):$this->noDataChar?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>