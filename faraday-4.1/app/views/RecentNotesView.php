<!-- .scrollable -->
<section class="panel" id="recent_notes">
  <header class="panel-heading h5 body_home" >Recent Notes </header>
  <section class="panel-body">
    <?php if ($notes[0]) { foreach ($notes as $nid => $note) { ?>
      <article class="media">
        <div class="pull-left thumb-small">
          <img src="<?=$note->profile_pic?($note->fullPicPath ? '' : base_url().$this->imagesPath).$note->profile_pic:(base_url().'assets/img/default-profile-pic.png')?>" width="40" />
        </div>
        <div class="media-body">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=convertDateTime($note->note_create_date, 'd')?></strong><br>
            <small class="label bg-light"><?=convertDateTime($note->note_create_date, 'M')?> <?=convertDateTime($note->note_create_date, 'y')?></small>
          </div>
          <a href="<?=base_url().$note->item_type?>s/details/<?=$note->item_id?>" class="h4"><?=$note->item_title?></a>
          <small class="block m-b-none"><?=$note->note?></small>
        </div>
      </article>
      <?php if ($nid < (count($notes) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } } else { ?>
      <p class="bcz-no-data-msg h5">No recent notes.</p>
    <?php } ?>
  </section>
</section>
<!-- / scrollable -->