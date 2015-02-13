<!-- .modal -->
<div id="create_folder_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>documents/createfolder">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Create Folder</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body text-center">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label m-t-mini text-right">Target Folder:<?=$this->mandatoryFieldIndicator?></label>
                <div class="btn-group col-sm-8">
                  <select name="folder_path" class="select2-option col-sm-12 no-padder" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($folders as $folder) {
						$folderpath=str_replace($this->uploadPath, '', $folder);
						$folderpath=str_replace($this->user->org_id."/", '', $folderpath);
						 ?>
                      <option value="<?=$folder?>"><?=$folderpath?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label">New Folder<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-sm-8">
                  <input type="text" name="folder_name" data-required="true" class="form-control">
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="create_folder" class="btn btn-sm btn-primary bcz-submit-btn">Create</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->