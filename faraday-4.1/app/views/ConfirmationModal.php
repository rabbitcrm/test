<!-- .modal -->
<div id="bcz_confirmation_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" <?php if ($this->bodyClass == 'docs') { echo 'action="'.base_url().'docs/deleteDoc"'; }else { ?> action=""<?php } ?>>
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
          <p>Do you really want to delete this item?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">No</button>
          <button type="button" class="btn btn-sm btn-primary">Yes</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->