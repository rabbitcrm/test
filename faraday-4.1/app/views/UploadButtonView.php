<form class="form-horizontal bcz-file-upload-form m-t-mini" method="post" data-validate="parsley" action="<?=base_url().$actionPath?>">
	<div class="col-xs-3 no-padder m-r-large hide upload-indicator" style="margin-top: 8px;">
		<div class="progress progress-small progress-striped active m-b-none">
			<div class="progress-bar progress-bar-success" data-toggle="tooltip" data-original-title="uploading..." style="width: 100%"></div>
		</div>
	</div>
	<input type="file" name="entity_doc" title="<?php $btnLabel?>Choose file" class="btn btn-sm btn-info" data-required="true">
	<?php if ($associateId) { ?><input type="hidden" name="associate_id" value="<?=$associateId?>"><?php } ?>
	<?php if ($associateTo) { ?><input type="hidden" name="associate_to" value="<?=$associateTo?>"><?php } ?>
  <input type="submit" class="btn btn-primary m-l" value="Upload">
</form>