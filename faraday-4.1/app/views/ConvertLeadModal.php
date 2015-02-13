<!-- .modal -->
<div id="convert_lead_modal" class="modal fade">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>leads/convert">
    <div class="modal-dialog">
      <div class="modal-content"><!-- .modal-content -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
          <h4 class="modal-title">Convert To Opportunity</h4>
        </div>
        <div class="modal-body">
          <section class="panel m-b-none">
            <div class="panel-body">
              <div class="alert ta-left alert-danger hide">
                <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <p><i class="icon-ban-circle icon-large m-r"></i><span class="bcz-status-msg"></span></p>
              </div>

              <div class="form-group">
                <label class="col-md-4 control-label">Opportunity Name<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-7">
                  <input type="text" name="deal_name" data-required="true" class="form-control" value="<?=$lead->company_name?>">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label">Opportunity Value<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-7">
                  <input type="text" name="deal_amount" data-required="true" class="form-control">
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label">Stage<?=$this->mandatoryFieldIndicator?></label>
                <div class="col-md-7">
                  <div class="btn-group col-xs-12 no-padder">
                    <select name="stage" class="select2-option" data-required="true">
                      <option value=""><?=$this->chooseOption?></option>
                      <?php foreach($fields as $stage) { if ($stage->stage) { ?>
                        <option value="<?=$stage->no?>"><?=$stage->stage?></option>
                      <?php } } ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-md-4 control-label">Target Date</label>
                <div class="col-md-7">
                  <input type="text" name="exp_close" class="form-control datepicker" value="<?=$this->today?>" data-date-format="dd-mm-yyyy" placeholder="<?=$this->chooseDate?>">
                </div>
              </div>

              <input type="hidden" name="lead_id" value="<?=$lead->lead_id?>">
            </div>
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Cancel</button>
          <button type="submit" id="convert_lead" class="btn btn-sm btn-primary bcz-submit-btn">Convert</button>
        </div>
      </div><!-- /.modal-content -->
    </div>
  </form>
</div>
<!-- / .modal -->