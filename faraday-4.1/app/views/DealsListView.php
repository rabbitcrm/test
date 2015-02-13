<!-- .scrollable -->
<section class="panel" id="your_tickets">
  <header class="panel-heading h5">Opportunities</header>
  <section class="panel-body">
    <?php if ($deals[0]) { foreach ($deals as $did => $deal) { ?>
      <article class="media">
        <div class="media-body">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=date("d", strtotime($deal->deal_create_date))?></strong><br>
            <small class="label bg-light"><?=date("M", strtotime($deal->deal_create_date))?> <?=date("y", strtotime($deal->deal_create_date))?></small>
          </div>
          <a href="<?=base_url()?>deals/details/<?=$deal->deal_id?>" class="h4"><?=$deal->deal_name?><span class="text-small m-l-mini text-warning"><?=$deal->stage?></span></a>
          <small class="block m-b-none"><?=$deal->summary?></small>
        </div>
      </article>
      <?php if ($did < (count($deals) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } } else { ?>
      <p class="bcz-no-data-msg h5">No Opportunities found.</p>
    <?php } ?>
  </section>
</section>
<!-- / scrollable -->