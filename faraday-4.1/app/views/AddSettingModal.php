<!-- .modal -->
<div id="add_setting_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>advancedsettings/addsetting">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Add a new <span class="setting-type"></span></h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <input type="text" name="setting_val" class="form-control" data-required="true" value="">
          <input type="text" name="probability" class="form-control hide m-t-small" value="" placeholder="Probability %">
          <input type="hidden" name="setting_type" class="form-control" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default btn-cancel-setting" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-sm btn-primary btn-submit-setting">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->