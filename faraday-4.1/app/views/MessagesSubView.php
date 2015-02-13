<? if(count($messages)) { ?>
	<div class="alert ta-left alert-<?=$type?>">
		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
		<? foreach($messages as $message) { $icon_type = ($type == 'success') ? 'ok-sign' : (($type == 'danger') ? 'ban-circle' : (($type == 'warning') ? 'bell-alt' : "$type-sign")); ?>
			<p><i class="icon-<?=$icon_type?> icon-large m-r"></i><?=$message?></p>
		<?php } ?>
	</div>
<? } ?>