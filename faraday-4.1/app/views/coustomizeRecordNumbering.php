<div class="row">
<div class="mes">
  <?php $this->load->view('MessagesView', array(messages => $messages)); ?>
</div>
  <form class="form-horizontal" method="post" data-validate="parsley" action="<?=base_url()?>advancedsettings/numbering" accept-charset="utf-8" enctype="multipart/form-data">
    <div class="col-sm-6">
    
    
            
            
      <div class="form-group">
        <label class="col-lg-3 control-label">Select Module<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
        
        <div class="btn-group col-xs-12 no-padder">
            
            
         <select name="module" class="select2-option sequence"  data-prefix="FA" data-sequence="1" data-required="true">
              <option value=""><?=$this->chooseOption?></option>
                <?php 
				if($PrefixSequence[0])
				{
					$count1=0;
					$count2=0;
					$count3=0;
				foreach($PrefixSequence as $Sequence) { if ($Sequence->module=="quote") { $count1=1; ?>
                
               <?php  $quote='<option value="quote" data-prefix="'.$Sequence->prefix.'" data-sequence="'.$Sequence->sequence .'" data-id="'.$Sequence->numbering_id.'">Quote</option>';
                } else if ($Sequence->module=="sales_order") { $count2=1; 
				
				 $sales_order='<option value="sales_order" data-prefix="'.$Sequence->prefix.'" data-sequence="'.$Sequence->sequence .'" data-id="'.$Sequence->numbering_id.'">Sales Order</option>'; } else if ($Sequence->module=="cases") { $count3=1; 
				  
				   $cases='<option value="cases" data-prefix="'.$Sequence->prefix.'" data-sequence="'.$Sequence->sequence .'" data-id="'.$Sequence->numbering_id.'">Ticket</option>';
				 }} if($count1==0){ 
				   echo '<option value="quote" data-prefix="QU" data-sequence="1" data-id="0">Quote</option>';
                   } else { echo $quote;}
				   if($count2==0){ 
                   echo '<option value="sales_order" data-prefix="SO" data-sequence="1" data-id="0">Sales Order</option> ';
                   } else { echo $sales_order;}
				    if($count3==0){ 
                     echo '<option value="cases" data-prefix="T" data-sequence="1" data-id="0">Ticket</option>';
                       } else { echo $cases;}  } else	{?>
                <option value="quote" data-prefix="QU" data-sequence="1" data-id="0">Quote</option>
                <option value="sales_order" data-prefix="SO" data-sequence="1" data-id="0">Sales Order</option>
                <option value="cases" data-prefix="T" data-sequence="1" data-id="0">Ticket</option>

                    <?php 
				}?>
                

            </select>
            
       </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Use Prefix<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="prefix" data-required="true" id="prefix" class="form-control" >
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label">Start Sequence<?=$this->mandatoryFieldIndicator?></label>
        <div class="col-lg-9">
          <input type="text" name="sequence" data-required="true" id="sequence" class="form-control" >
          <input type="hidden" name="numbering_id" data-required="true" id="numbering" class="form-control" >
        </div>
      </div>
      
      
      
      
    </div>

    

    <div class="col-sm-8">
      <input type="hidden" name="org_id" value="<?=$organization->id?$organization->id:$organization->sno?>">
      <button type="submit" class="btn btn-primary m-l" id="save_org">Save</button>
    </div>
  </form>
</div>