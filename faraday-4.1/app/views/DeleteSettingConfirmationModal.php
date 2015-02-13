<!-- .modal -->
<div id="delete_setting_confirmation_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>advancedsettings/deletesetting">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
          <p>Do you want to delete this <span class="setting-type"></span>?</p>
          <input type="hidden" name="setting_type" class="form-control" value="">
          <input type="hidden" name="setting_id" class="form-control" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default btn-cancel-setting" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary btn-submit-setting">Delete</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->