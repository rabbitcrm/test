<!-- .scrollable -->
<section class="panel" id="your_tickets">
  <header class="panel-heading h5">Tickets</header>
  <section class="panel-body">
     <input type="hidden" class="export" value="0" />
    <?php if ($cases[0]) { foreach ($cases as $cid => $case) { $textPostfix = ($case->severity == 'Critical') ? 'danger' : (($case->severity == 'Major') ? 'warning' : (($case->severity == 'Minor') ? 'success' : 'default')); ?>
      <article class="media">
        <div class="pull-left thumb-small">
          <span class="icon-stack">
            <i class="icon-circle text-<?=$textPostfix?> icon-stack-base"></i>
            <i class="icon-ticket icon-light"></i>
          </span>
        </div>
        <div class="media-body">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=date("d", strtotime($case->case_create_date))?></strong><br>
            <small class="label bg-light"><?=date("M", strtotime($case->case_create_date))?> <?=date("y", strtotime($case->case_create_date))?></small>
          </div>
          <a href="<?=base_url()?>cases/details/<?=$case->case_id?>" class="h4"><?=$case->case_title?></a>
          <small class="block m-b-none"><?=$case->case_description?></small>
        </div>
      </article>
      <?php if ($cid < (count($cases) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } } else { ?>
      <p class="bcz-no-data-msg h5">No tickets found.</p>
    <?php } ?>
  </section>
</section>
<!-- / scrollable -->