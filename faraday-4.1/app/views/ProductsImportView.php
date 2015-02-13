<div class="clearfix">
  <h3>Import Products</h3>
</div>
<?php print_r( $messages); ?>
<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>products/importcsv" enctype="multipart/form-data">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Products File<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
               <input type="file" name="import_file" id="import_file" accept=".csv" />
               <div id="error"></div>
              </div>
             
            </div>
            
           
            
            
            
          </div>
        </div>
      </section>
    </div>

    <div class="col-sm-12">
      <div class="form-group m-b-small">
        <button type="submit" class="btn btn-primary m-l">Import</button>
      </div>
    </div>
  </form>
</div>