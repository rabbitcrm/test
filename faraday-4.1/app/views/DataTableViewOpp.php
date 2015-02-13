
<?php if($filter)
{  ?> <div class="bcz-filters-content3"><?php } ?>
<section class="panel">
  <div class="table-responsive">
  <div class="panel-body bcz-filters-con table1" data-filter-action="<?=base_url().$actionPath?>">
 <table class="table table-striped m-b-none <?php if($className){?><?=$className?> <?php }else{  ?>bcz-data-table bcz-data-table1<?php }?>" data-cache="false" data-ride="datatables" <?php $colWidth = 100/count($cols); if ($sourcePath) { ?>   data-source="<?=base_url().$sourcePath?>" <?php } ?> >
 
 <?php if(!$thead) {?>
      <thead>
        <tr>
          <?php  foreach ($cols as $colId => $col) { ?>
            <th width="<?=$colWidth?>%" class=" <?=($mobileCols && !in_array($colId, $mobileCols))?'hidden-xs':''?> <?=($col=='actions')?'text-center bcz-row-actions' : ''?>"><?php if($currency_freeze['currency']!=""){ { if((ucfirst($col)=='Amount')Or (ucfirst($col)=='Price')){ $col=$col."(".$currency_freeze['currency'].")"; }}} if((ucfirst($col)=='Tax_type')){ $col='Tax type'; }else if((ucfirst($col)=='Vat')){ $col='Tax %'; } ?><?=ucfirst($col)?> </th>
          <?php } ?>
        </tr>
      </thead>
      <?php } ?>
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
<?php if($filter)
{  ?> <?php /*?><div class="panel-body bcz-filters" data-filter-action="<?=base_url()?>">
	<select name="deal_create_date" class="<?=1?> select2-option filter-opp">
                <option value="">All</option>
                <option value="curr_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="curr_week">This Week</option>
                <option value="last_week">Last Week</option>
                <option value="90_days">Last 90 Days</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>
                </select>
                </div><?php */?>
<?php }?>
</div>
  </div>
</section>

<?php if($filter)
{  ?> </div> <?php } ?>

