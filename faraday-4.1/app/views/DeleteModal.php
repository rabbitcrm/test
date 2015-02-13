<!-- .modal -->
<div id="delete_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>advancedsettings/addUser">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Confirmation</h4>
        </div>
        <div class="modal-body">
          <div class="alert ta-left alert-danger hide">
            <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
            <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
          </div>

          <div class="form-group">
         &nbsp;&nbsp;Admin Account can't be deleted
            
          </div>
          
          
          
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
         
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->