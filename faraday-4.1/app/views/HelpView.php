
<div class="clearfix">
  <h3>Help / Support</h3>
</div>

<div class="row">

  <div class="col-sm-12">      
    <section class="panel">
    	<div class="panel-body">
        <div class="col-sm-6">
          <span class="thumb-small avatar inline col-sm-6">   <img src="<?=base_url().'assets/img/phone.png'?>" alt="phone" class="img-circle" /></span>
          <h4 class="col-sm-6"><?=$this->supportInfo['contact']?></h4>
          <div style="clear:both"></div>
         <span class="thumb-small avatar inline col-sm-6"><img src="<?=base_url().'assets/img/mail.png'?>" alt="email" class="img-circle" /></span>
          <h4 class="col-sm-6"><?=$this->supportInfo['email']?></h4>
         
        </div>
    	</div>
    </section>
  </div>
</div>



<div class="clearfix">
  <h3>Query/Feedback Form</h3>
</div>
<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesSubView', array(messages => $messages, type => 'success')) ?></div></div>

<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>help/submit">
 <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
          <div class="col-sm-6">
          
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Name<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
               <input type="text" name="name" data-required="true" class="form-control" value="<?=$this->user->name?ucfirst($this->user->name):$this->user->username?>" >
               
              </div>
            </div>
            
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Phone</label>
              <div class="col-lg-9">
               <input type="text" name="phone" class="form-control" data-type="number">
               
              </div>
            </div>
            
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label">Email<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
               <input type="text" name="email" data-required="true" class="form-control"  value="<?=$this->user->user_email?>" >
               
              </div>
            </div>
            
          <div class="form-group m-b-small">
                    <label class="col-lg-3 control-label">Comments<?=$this->mandatoryFieldIndicator?></label>
                    <div class="col-lg-9">
                      <textarea name="comments" rows="3" class="form-control" data-trigger="keyup" data-rangelength="[20,1000]" data-required="true"></textarea>
         </div>
         </div>
          </div>
          </div>
          </section>
          </div>
          <div class="col-sm-12">
      <div class="form-group">
        <button type="submit" class="btn btn-primary m-l" >Send</button>
      </div>
    </div>
          </form>
          
          </div>