<div class="panel-heading fieldset-head ">
  <a class="accordion-toggle h4" data-toggle="collapse" data-parent="<?=$parent?>" href="<?=$href?>">
    <?=$headingText?> <span class="caret m-t-none m-l-mini"></span>

    <?php if ($actionButtons) {
    	foreach ($buttons as $button) {
    	if ($button['actionPath']) {
    		$this->load->view('UploadButtonView', array('btnLabel' => $button['text'], 'actionPath' => $button['actionPath'], 'associateTo' => $button['associateTo'], 'associateId' => $button['associateId']));
    	} else { ?>
			<button class="btn btn-inverse btn-xs pull-right bcz-add-item-entity <?=$button['class']?$button['class']:''?>" data-associate-to="<?=$button['associateTo']?>" data-associate-id="<?=$button['associateId']?>" <?php if($button['account']!=""){ ?>data-account-id="<?=$button['account']?>" <?php }  if ($button['modalId']) { ?>data-href="<?=$button['modalId']?>" <?php } elseif ($button['path']) { ?> data-redirect-url="<?=base_url().$button['path']?>"<?php } ?>><i class="icon-plus"></i> <?=strtoupper($button['text'])?></button>
    	<?php } ?>
    <?php } } ?>
  </a>
</div>