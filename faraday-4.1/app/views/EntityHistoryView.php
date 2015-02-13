<?php if ($history) { ?>
	<div class="col-sm-10 col-sm-offset-1">
	<?php $hno = 0; foreach($history as $hid => $hdata) { ?>
		<div class="text-primary h4" <?php if ($hno) { ?>style="margin-top: 20px;"<?php } ?>><?=(strpos($hid, '_'))?ucwords(strtolower(str_replace('_', ' ', $hid))).'s':ucfirst(strtolower($hid))?> History</div>

		<?php $hino = 0; foreach($hdata as $key => $hitem) { ?>
			<article class="media">
				<div class="media-body">
					<div class="pull-left">
						<img src="<?=$hitem->profile_pic?base_url().$this->imagesPath.$hitem->profile_pic:(base_url().'assets/img/default-profile-pic.png')?>" width="40" />
					</div>

					<p class="h5" style="margin-left: 50px;">
						<strong><?=ucfirst($hitem->name?$hitem->name:$hitem->username)?></strong> <?=(strpos($hid, '_'))?strtolower(str_replace('_', (strstr($hid, 'E_')?'d':'ed').' a ', $hid)).(strstr($hid, 'DELETE')?' from ':' to '):strtolower($hid).((substr($hid, strlen($hid)-1) == 'E')?'d':'ed')?> this <?=$entityType?> <?=($hid == 'REASSIGN') ? ' from '.$aUsers[$hitem->info->from]->name.' to '.$aUsers[$hitem->info->to]->name : ''?> on <?=convertDateTime($hitem->create_date)?>
					</p>
				</div>
			</article>
			<?php if ($hino < (count($hdata) - 1)) { ?><div class="line pull-in"></div><?php } ?>
		<?php $hino++; } ?>

		<?php if ($hno < (count($history) - 1)) { ?><div class="line pull-in m-b-none m-t"></div><div class="line pull-in m-t-none"></div><?php } ?>
	<?php $hno++; } ?>
	</div>
<?php } else { ?>
	<p class="bcz-no-data-msg h5">No history exists yet.</p>
<?php } ?>