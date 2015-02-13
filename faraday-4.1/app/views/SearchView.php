<div class="clearfix">
  <h3>Search results (<?=count($matches)?>)</h3>
</div>

<?php if ($matches[0]) { ?>

  <section class="panel">
    <section class="panel-body">
      <?php 
	  $cid="";
	
	  foreach ($matches as $mid => $match) { 
	 
	  if($match->id!=$cid)
	  {
		  $cid=$match->id;
          switch ($match->type) {
            case 'Opportunity':
                $labelType = 'success';
                break;
            case 'lead':
                $labelType = 'info';
                break;
            case 'contact':
                $labelType = 'warning';
                break;
            case 'Account':
                $labelType = 'primary';
                break;
			case 'Ticket':
                $labelType = 'danger';
                break;
          }
		  
		  if($match->name!="")
		  {
        ?>
        
        
        
        <article class="media">
          <div class="media-body">
            <div class="pull-right media-mini text-center text-muted">
              <strong class="h4"><?=date("d", strtotime($match->date))?></strong><br>
              <small class="label bg-light"><?=date("M", strtotime($match->date))?> <?=date("y", strtotime($match->date))?></small>
            </div>
            <a href="<?=base_url().$match->urlPrefix.'/'.$match->id?>" class="h4"><?=$match->name?><span class="label label-<?=$labelType?> m-l" style="font-size: 60%;"><?=$match->type?></span></a>
            <p class="block m-b-none"><?=$match->summary?></p>
          </div>
        </article>
        <?php  ?>
        <?php if ($mid < (count($matches) -1)) { ?><div class="line pull-in"></div>
		<?php } }  } } ?>
    </section>
  </section>

<?php } else {

  $this->load->view('NoDataView');

} ?>