<div class="clearfix">
  <h3>Companies Mapping</h3>
</div>

<div class="row"><div class="col-sm-6"><? $this->load->view('MessagesView', array(messages => $messages)) ?></div></div>
<div class="row">
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>companies/importmapping" enctype="multipart/form-data">
    <div class="col-sm-12">      
      <section class="panel">
        <div class="panel-body">
      
         <?php foreach($company_table_data as $table_data1) { if($table_data1['value']!="")
			 {
			  ?>
           <div class="col-sm-6">
          <div class="form-group m-b-small">
              <label class="col-lg-3 control-label"><?=$table_data1['value']?></label>
              <div class="col-lg-9">
             <div class="btn-group col-xs-11 no-padder">
                    <select id="<?=$table_data1['col']?>" name="<?=$table_data1['col']?>" class="select2-option option-select" >
                    <option value=""><?=$this->chooseOption?></option>
           <?php foreach($company_table_data as $table_data) { ?>
         
            <option value="<?=$table_data['col']?>"><?=$table_data['name']?></option>
                    <?php }?>
                  </select>

                </div>
              </div>
            </div>
          </div>
           
          
         <?php } } ?>
         <div class="clear" ></div>
                  <div class="clearfix">
  <h4>Associative Mapping</h4>
</div>
     <br/>  
            <div class="col-sm-6">
          <div class="form-group m-b-small">
             <label class="col-lg-3 control-label">Assigned to<?=$this->mandatoryFieldIndicator?></label>
              <div class="col-lg-9">
             <div class="btn-group col-xs-11 no-padder">
                    <select name="owner_id" class="select2-option" data-required="true" >
                    <option value=""><?=$this->chooseOption?></option>
                    <?php foreach($users as $user) { ?>
                      <option value="<?=$user->user_id?>" <?php if ($user->user_id == $this->user->user_id) { ?> selected="selected"<?php } ?>><?=$user->name?></option>
                    <?php } ?>
                  </select>

               
                </div>
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