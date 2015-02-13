<!-- .modal -->
<div id="add_file_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>files/addfile">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Upload File</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body text-center">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>

              <div class="form-group">
                <div class="col-sm-8 text-left">
                  <input type="file" name="new_doc" title="Select" class="btn btn-sm btn-info m-b-small col-sm-6" data-required="true">
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="add_file" class="btn btn-sm btn-primary bcz-submit-btn">Upload</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->