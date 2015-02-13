<!-- .modal -->
<div id="generate_so_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>quotes/generateso">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Generate SO</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body text-center">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>

              <label class="col-xs-6 control-label m-t-mini text-right">PO Reference:<?=$this->mandatoryFieldIndicator?></label>
              <div class="btn-group col-xs-6">
                <div class="btn-group col-xs-12 no-padder">
                  <select name="po_reference" class="select2-option" data-required="true">
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($fields as $po_reference) { if ($po_reference->po_reference) { ?>
                      <option value="<?=$po_reference->po_reference?>"><?=$po_reference->po_reference?></option>
                    <?php } } ?>
                  </select>
                </div>
              </div>
              <input type="hidden" name="quote_id" value="<?=$quote->quote_id?>">
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="generate_so" class="btn btn-sm btn-primary bcz-submit-btn" data-loading-text="Generating...">Generate</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->