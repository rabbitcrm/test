<!-- .scrollable -->
<section class="" id="top_deals">
  <header class="panel-heading h5 body_home">Top Opportunities <div class="float_right">Amount</div></header>
 
   <?php /*?> <?php if ($deals[0]) { foreach ($topDeals as $did => $deal) { ?>
      <article class="media">
        <div class="media-body">
          <div class="pull-right media-mini text-center text-muted">
            <small class="label bg-primary" title="<?=$deal->amount?>"><?=$deal->deal_amount?></small>
          </div>
          <a title="<?=$deal->deal_name?>" href="<?=base_url()?>deals/details/<?=$deal->deal_id?>" ><div class="bcz-text-ellipsis"><?=$deal->deal_name?></div></a>
        </div>
      </article>
      <?php if ($did < (count($deals) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } } else { ?>
      <p class="bcz-no-data-msg h5">No opportunities found.</p>
    <?php } ?><?php */?>
    
     <?php 
	 $this->load->view('DataTableViewOpp', array('className'=>'top_opp','thead'=>'false','cols'=>$topoppcols,'sourcePath' => 'dashboard/gettopopp','filter' => 'filter')); ?>

</section>
<!-- / scrollable -->

