<!-- .modal -->
<div id="compose_email_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>emails/send">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Compose Email</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>

              <div class="form-group hide">
                <label class="col-md-3 control-label">From<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-7">
                  <input type="text" name="from" data-required="true" class="form-control" value="<?=$from?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">To<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-7">
                  <input type="email" name="to" data-required="true" class="form-control" value="<?=$to?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Cc</label>
                <div class="col-md-7">
                  <input type="email" name="cc" class="form-control" value="<?=$cc?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Bcc</label>
                <div class="col-md-7">
                  <input type="email" name="bcc" class="form-control" value="<?=$bcc?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-3 control-label">Subject<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-7">
                  <input type="text" name="subject" data-required="true" class="form-control" value="<?=$subject?>">
                </div>
              </div>
              <?php if ($type == 'quote') { ?>
                <div class="form-group m-b-small">
                  <label class="col-md-3 control-label">Quotation</label>
                  <label class="col-md-7 control-label"><span class="pull-left"><?=$pdf_name?></span></label>
                  <input type="hidden" name="defaultAttachment" value="<?=$this->quotesPath.$pdf_name?>">
                </div>
              <?php } else if ($type == 'order') { ?>
                <div class="form-group m-b-small">
                  <label class="col-md-3 control-label">Sales Order</label>
                  <label class="col-md-7 control-label"><span class="pull-left"><?=$pdf_name?></span></label>
                  <input type="hidden" name="defaultAttachment" value="<?=$this->ordersPath.$pdf_name?>">
                </div>
              <?php } ?>
              <div class="form-group">
                <label class="col-sm-3 control-label">Attachment</label>
                <div class="col-sm-9 text-left">
                  <input type="file" name="attachment" title="Add File" class="btn btn-sm btn-info m-b-small">
                </div>
              </div>
              <div class="form-group">
                <div class="col-sm-12 text-left">
                  <textarea name="message" data-required="true" rows="5" class="form-control" data-trigger="keyup"><?=$message?></textarea>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="contact_id" value="<?=$contact_id?>">
          <input type="hidden" name="company_id" value="<?=$company_id?>">
          <input type="hidden" name="deal_id" value="<?=$deal_id?>">
          <input type="hidden" name="quote_id" value="<?=($type == 'quote')?$id:''?>">
          <input type="hidden" name="sales_order_id" value="<?=($type == 'order')?$id:''?>">
          <input type="hidden" name="type" value="<?=$type?>" />
          <input type="hidden" name="id" value="<?=$id?>" />
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="send_email" class="btn btn-sm btn-primary bcz-submit-btn">Send</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->