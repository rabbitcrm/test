<section class="panel">
  <div class="table-responsive">
    <table class="table table-striped m-b-none bcz-data-table" data-ride="datatables" <?php if ($sourcePath) { ?>data-source="<?=base_url().$sourcePath?>"<?php } ?>>
      <thead>
        <tr>
          <?php $colWidth = 100/count($cols); foreach ($cols as $colId => $col) { ?>
            <th width="<?=$colWidth?>%" class="<?=($mobileCols && !in_array($colId, $mobileCols))?'hidden-xs':''?> <?=($col=='actions')?'text-center bcz-row-actions' : ''?>"><?php if($currency_freeze['currency']!=""){ { if((ucfirst($col)=='Amount')Or (ucfirst($col)=='Price')){ $col=$col."(".$currency_freeze['currency'].")"; }}} ?><?=ucfirst($col)?> </th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
      
        <?php if ($rows[0]) { ?>
          <?php foreach ($rows as $rid => $row) { ?>
            <tr data-rowid="<?=($row->id)?>">
              <?php foreach ($cols as $cid => $col_name) { ?>
                <td width="<?=$colWidth?>%" class="<?=is_array($row->$col_name)?'text-center bcz-row-actions':''?>">
                  <?php if (is_array($row->$col_name)) { ?>
                    <a href="#" data-href="<?=base_url().'settings/edituser/'.$row->id?>" data-bczajax-modal="true" data-action="edit"><i class="icon-edit-sign icon-large m-r-small"></i></a>
                    <?php if(is_array($row->$col_name)!="Admin") { ?>
                    <a href="#delete_user_confirmation_modal" data-toggle="modal" data-action="delete"><i class="icon-remove-sign icon-large"></i></a>
                  <? }  } else { ?>
                    <?php if ($urlFlag && ($cid == ($colIndex-1))) { ?><a class="text-primary" href="<?=base_url()?><?=$urlPrefix?>/<?=$row->id?>"><?php } ?><?=$row->$col_name?><?php if ($urlFlag && ($colIndex-1)) { ?></a><?php } ?>
                  <?php } ?>
                </td>
              <?php } ?>
          <?php } ?>
        <?php } ?>
      </tbody>
    </table>
<!--
    <table class="table table-striped m-b-none bcz-data-table" data-ride="datatables">
      <thead>
        <tr>
          <?php $colWidth = 100/count($cols); foreach ($cols as $colId => $col) { ?>
            <th width="<?=$colWidth?>%" class="<?=($colId == ($colIndex-1))?'':'hidden-xs'?>"><?=ucfirst($col)?></th>
          <?php } ?>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($rows as $rid => $row) { ?>
          <tr data-rowid="<?=($row->id)?>">
            <?php foreach ($cols as $cid => $col_name) { ?>
              <td width="<?=$colWidth?>%" class="<?=($cid == ($colIndex-1))?'':'hidden-xs'?> <?=is_array($row->$col_name)?'text-center bcz-row-actions':''?>">
                <?php if (is_array($row->$col_name)) { ?>
                  <a href="#" data-href="<?=base_url().'settings/edituser/'.$row->id?>" data-bczajax-modal="true" data-action="edit"><i class="icon-edit-sign icon-large m-r-small"></i></a>
                  <a href="#delete_user_confirmation_modal" data-toggle="modal" data-action="delete"><i class="icon-remove-sign icon-large"></i></a>
                <? } else { ?>
                  <?php if ($urlFlag && ($cid == ($colIndex-1))) { ?><a class="text-primary" href="<?=base_url()?><?=$urlPrefix?>/<?=$row->id?>"><?php } ?><?=$row->$col_name?><?php if ($urlFlag && ($colIndex-1)) { ?></a><?php } ?>
                <?php } ?>
              </td>
            <?php } ?>
        <?php } ?>
      </tbody>
    </table>
-->    
  </div>
</section>