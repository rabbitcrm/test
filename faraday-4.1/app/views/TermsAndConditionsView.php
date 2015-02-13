<div class="row">
<div class="mes">
  <?php $this->load->view('MessagesView', array(messages => $messages)); ?>
</div>
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>advancedsettings/termsandconditions" accept-charset="utf-8" enctype="multipart/form-data">
  
  
    <div class="col-sm-6">
      
      <div class="form-group">
        
        <div class="col-lg-12">
          <textarea name="terms_conditions" placeholder="Terms &amp; Conditions" rows="5" style="width:100%" class="form-control col-sm-12"><?=$terms_conditions; ?></textarea>

        </div>
      </div>
      
    </div>

    <div class="col-sm-8">
     <button type="submit" class="btn btn-primary m-l" id="save_org">Save</button>
    </div>
  </form>
</div>