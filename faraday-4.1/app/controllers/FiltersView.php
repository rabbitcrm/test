
<section class="panel">
  <div class="panel-body bcz-filters" data-filter-action="<?=base_url().$actionPath?>">
    <div class="col-sm-1 no-padder m-t-large">
      <div class="form-group h4 m-b-none m-t-mini">
        <label class="control-label">Filters:</label>
      </div>
    </div>

    <div class="col-sm-11 no-padder-r">
      <?php $colWidth = min(4, (12 / count($filters)));$ik=0; foreach ($filters as $fLabel => $fData) { $ik++; ?>
        <div class="col-sm-<?=$colWidth?> m-t-mini m-b-mini no-padder-l <?=($mobFilters && !in_array($fData['col'], $mobFilters))?'hidden-xs':''?>">
          <label class="col-lg-12 control-label m-t-mini m-r-mini no-padder"><?=ucfirst($fLabel)?>:</label>
         
          <div class="col-lg-12 no-padder">
            <div class="btn-group col-xs-12 no-padder">
            <?php if(($_SESSION['filters_key'][$ik]==$fData['col'])&&($_SESSION['filters_fvalue'][$ik]!=""))
			{ 
				?>
                <select name="<?=$fData['col']?>" class="<?=$ik?> select2-option">
                <option value="">All</option>

                <?php
                  if ($fData['type'] == 'date') {
                ?>

                <option value="curr_month" <?php if($_SESSION['filters_fvalue'][$ik]=='curr_month') { ?> selected="selected" <?php } ?>>This Month</option>
                <option value="last_month" <?php if($_SESSION['filters_fvalue'][$ik]=='last_month') { ?> selected="selected" <?php } ?>>Last Month</option>
                <option value="curr_week" <?php if($_SESSION['filters_fvalue'][$ik]=='curr_week') { ?> selected="selected" <?php } ?>>This Week</option>
                <option value="last_week" <?php if($_SESSION['filters_fvalue'][$ik]=='last_week') { ?> selected="selected" <?php } ?>>Last Week</option>
                <option value="90_days" <?php if($_SESSION['filters_fvalue'][$ik]=='90_days') { ?> selected="selected" <?php } ?>>Last 90 Days</option>
                <option value="today" <?php if($_SESSION['filters_fvalue'][$ik]=='today') { ?> selected="selected" <?php } ?>>Today</option>
                <option value="yesterday" <?php if($_SESSION['filters_fvalue'][$ik]=='yesterday') { ?> selected="selected" <?php } ?>>Yesterday</option>

                <?php
                  }
				   else if($fData['type'] == 'close_date') {
                ?>

                <option value="curr_month" <?php if($_SESSION['filters_fvalue'][$ik]=='curr_month') { ?> selected="selected" <?php } ?>>This Month</option>
                <option value="next_month" <?php if($_SESSION['filters_fvalue'][$ik]=='next_month') { ?> selected="selected" <?php } ?>>Next Month</option>
                <option value="curr_week" <?php if($_SESSION['filters_fvalue'][$ik]=='curr_week') { ?> selected="selected" <?php } ?>>This Week</option>
                <option value="next_week" <?php if($_SESSION['filters_fvalue'][$ik]=='next_week') { ?> selected="selected" <?php } ?>>Next Week</option>
                <option value="today" <?php if($_SESSION['filters_fvalue'][$ik]=='today') { ?> selected="selected" <?php } ?>>Today</option>
                <option value="tomorrow" <?php if($_SESSION['filters_fvalue'][$ik]=='tomorrow') { ?> selected="selected" <?php } ?>>Tomorrow</option>

                <?php
                  }
				  else if ($fData['type'] == 'Deliverydate')
				  {
					    ?>

                <option value="curr_month" <?php if($_SESSION['filters_fvalue'][$ik]=='curr_month') { ?> selected="selected" <?php } ?>>This Month</option>
                <option value="curr_week" <?php if($_SESSION['filters_fvalue'][$ik]=='curr_week') { ?> selected="selected" <?php } ?>>This Week</option>
                <option value="today" <?php if($_SESSION['filters_fvalue'][$ik]=='today') { ?> selected="selected" <?php } ?>>Today</option>
                <?php
				  }
				  
				   else {
                  $fOptions = array();
                  foreach ($fSource as $fRow) {
                    if ($fRow->$fData['col'] && !in_array($fRow->$fData['col'], $fOptions)) {                  
                      $fOptions[] = $fRow->$fData['col'];
				
					 if($fData['user_status']!='')
					{ 
					if($fRow->$fData['user_status']!='inactive')
					{ 

                ?>

                  <option value="<?=$fRow->$fData['col']?>" <?php if($_SESSION['filters_fvalue'][$ik]==$fRow->$fData['col']) { ?> selected="selected" <?php } ?>><?=$fRow->$fData['alias']?></option>

                <?php } }
				else { ?>  <option value="<?=$fRow->$fData['col']?>" <?php if($_SESSION['filters_fvalue'][$ik]==$fRow->$fData['col']) { ?> selected="selected" <?php } ?>><?=$fRow->$fData['alias']?></option>
 <?php } } } } ?>    
                
              </select>
                <?php
			}else {?>
            
              <select name="<?=$fData['col']?>" class="<?=$ik?> select2-option">
                <option value="">All</option>

                <?php
                  if ($fData['type'] == 'date') {
                ?>

                <option value="curr_month">This Month</option>
                <option value="last_month">Last Month</option>
                <option value="curr_week">This Week</option>
                <option value="last_week">Last Week</option>
                <option value="90_days">Last 90 Days</option>
                <option value="today">Today</option>
                <option value="yesterday">Yesterday</option>

                <?php
                 
                  }
				   else if($fData['type'] == 'close_date') {
                ?>

                <option value="curr_month" >This Month</option>
                <option value="next_month">Next Month</option>
                <option value="curr_week">This Week</option>
                <option value="next_week" >Next Week</option>
                <option value="today" >Today</option>
                <option value="tomorrow" >Tomorrow</option>

                <?php
                  }
				  else if ($fData['type'] == 'Deliverydate')
				  {
					    ?>

                <option value="curr_month">This Month</option>
                <option value="curr_week">This Week</option>
                <option value="today">Today</option>
                <?php
				  }
				  
				   else {
                  $fOptions = array();
                  foreach ($fSource as $fRow) {
                    if ($fRow->$fData['col'] && !in_array($fRow->$fData['col'], $fOptions)) {                  
                      $fOptions[] = $fRow->$fData['col'];
					 if($fData['user_status']!='')
					{ 
					if($fRow->$fData['user_status']!='inactive')
					{ 

                ?>

                  <option value="<?=$fRow->$fData['col']?>"><?=$fRow->$fData['alias']?></option>

                 <?php } }
				else { ?>    <option value="<?=$fRow->$fData['col']?>"><?=$fRow->$fData['alias']?></option>
 <?php }  } } } ?>
                
              </select>
             <?php  } ?>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</section>