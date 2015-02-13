<!-- .scrollable -->
<section class="panel" id="recent_posts">
  <header class="panel-heading h5 body_home">Latest Posts</header>
  <section class="panel-body">
    <?php if ($posts[0]) { foreach ($posts as $pid => $post) { ?>
      <article class="media">
        <div class="pull-left thumb-small">
          <img src="<?=$post->profile_pic?($post->fullPicPath ? '' : base_url().$this->imagesPath).$post->profile_pic:(base_url().'assets/img/default-profile-pic.png')?>" width="40" />
        </div>
        <div class="media-body">
          <div class="pull-right media-mini text-center text-muted">
            <strong class="h4"><?=convertDateTime($post->posted_on, 'H:i:s')?></strong><br>
            <small class="label bg-light"><?=convertDateTime($post->posted_on, 'd').', '.convertDateTime($post->posted_on, 'M')?> <?=convertDateTime($post->posted_on, 'y')?></small>
          </div>
          <div class="block m-b-none">
            <?=$post->post_body?>
            <?php if ($post->file_id) { ?>
              <p class="m-t-mini m-b-none">
                <?php 
                  $extension = strtolower(end(explode('.', $post->filename)));
                  if ($extension == 'xlsx') $extension = 'xls'; 
                  if ($extension == 'docx') $extension = 'doc'; 
                  $iconname = in_array($extension, array('jpeg', 'jpg', 'png', 'gif', 'bmp')) ? '' : $extension.'-icon.png';
                ?>
                <img src="<?=base_url().($iconname ? 'assets/img/'.$iconname : $this->postFilesPath.$post->filename)?>" width="30" />
                <small class="m-r-mini m-l-small"><a target="_blank" href="<?=base_url()?>files/view?type=post_file&name=<?=$post->filename?>"><i class="icon-eye-open icon"></i>View</a></small>
                <small><a href="<?=base_url()?>files/download?type=post_file&name=<?=$post->filename?>"><i class="icon-download-alt icon"></i>Download</a></small>
              </p>
            <?php } ?>
          </div>
        </div>
      </article>
      <?php if ($pid < (count($posts) -1)) { ?><div class="line pull-in"></div><?php } ?>
    <?php } } else { ?>
      <p class="bcz-no-data-msg h5">No recent posts.</p>
    <?php } ?>
  </section>
</section>
<!-- / scrollable -->