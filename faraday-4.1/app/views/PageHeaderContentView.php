<div class="pull-left m-b-mini">
	<h3 class="disp-i-b"><?=$title?></h3>
	Created<?php if ($owner) { ?> By <span class="text-danger"><?=ucfirst($owner)?></span><?php } ?><?php if ($date) { ?> On <span class="text-danger"><?=convertDateTime($date, 'd/m/Y h:i:s')?></span><?php } ?>
</div>