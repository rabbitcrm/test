<!-- .modal -->
<div id="reset_user_confirmation_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>settings/resetuser">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
          <p>Do you want to reset this user's password?</p>
          <input type="hidden" name="user_id" class="form-control" value="">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button id="reset_user" type="submit" class="btn btn-sm btn-primary">Reset</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->