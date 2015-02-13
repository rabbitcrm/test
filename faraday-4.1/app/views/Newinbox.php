<section class="">
 <header class="panel-heading"> 
 <ul class="nav nav-tabs nav-justified">
  <li class="active body_home"><a class="body_home" href="#New_Leads" data-toggle="tab">New Leads</a></li>
  <li class=" body_home"><a class="body_home" href="#New_Opportunities" data-toggle="tab">New Opportunities</a></li> 
  <li class=" body_home"><a class="body_home" href="#New_Accounts" data-toggle="tab">New Accounts</a></li>
  
   </ul> 
   </header> 
 
   <div class="tab-content">
    <div class="tab-pane active" id="New_Leads">
    
      
    <?php if ($newleads[0]) { 
	
	?>
   
    <?php 
	 $this->load->view('DataTableViewOpp', array('className'=>'home_lead','cols'=>$Lcols,'sourcePath' => 'dashboard/getleadjson')); ?>
	
    <?php /*?><table class="table table-striped m-b-none bcz-data-table" data-page-list="[5]">
    <thead>
    <th>Name</th>
    
    <th>Owner</th>
    <th>Status</th>
    <th>Date</th>
   
    </thead>
    <tbody>
   
    <?php
	
	foreach ($newleads as $newlead) { ?>
    
    <div class="media-body">
    <tr> <td class="task_tital">
    <a title="<?=$newlead->last_name;?>" href="<?=base_url()?>leads/details/<?=$newlead->lead_id?>" ><?=substr($newlead->lead_name, 0, 18)?></a>
      </td>
      
   
       <td class="task_company"><small class="block m-b-none"><?=$newlead->name?></small></td>
      
        <td class="task_priority"><small class="block m-b-none"><?=$newlead->lead_status?></small></td>
        
         <td class="task_company"><small class="block m-b-none"><?=convertDateTime($newlead->lead_create_date, 'd').', '.convertDateTime($newlead->lead_create_date, 'M')?> <?=convertDateTime($newlead->lead_create_date, 'y')?>
		 
		</small></td>
      
      
        
      </tr>
   </div>
    
        
        
  
    <? } ?></tbody></table><?php */?> <?php } else { ?><p class="bcz-no-data-msg h5">No new lead.</p> <?php } ?>
    
    
    </div> 
    <div class="tab-pane" id="New_Opportunities">
    
    
    
        <?php if ($newldeals[0]) { 
	$this->load->view('DataTableViewOpp', array('className'=>'home_opp','cols'=>$Ocols,'sourcePath' => 'dashboard/getdealsjson'));
	?>
    
    <?php /*?><table class="table table-striped m-b-none bcz-data-table">
    <thead>
    <th>Name</th>
    
    <th>Owner</th>
    <th>Stage</th>
    <th>Amount</th>
    
    </thead>
    <tbody>
    <?php
	
	foreach ($newldeals as $newldeal) { ?>
    
    <div class="media-body">
    <tr> <td class="task_tital">
   
        
          <a title="<?=$newldeal->deal_name;?>" href="<?=base_url()?>deals/details/<?=$newldeal->deal_id?>" ><?=substr($newldeal->deal_name, 0, 18)?></a>
        
     
      </td>
      
      <td class="task_company"><small class="block m-b-none"><?=$newldeal->name?></small></td>
      
      <td class="task_company"><small class="block m-b-none"><?=$newldeal->stage?></small></td>
      
       
      
        <td class="task_priority"><small class="block m-b-none"><?=$newldeal->deal_amount?></small></td>
        
      
        
  
      </tr>
      </div>
    
        
        
  
    <? } ?></tbody></table><?php */?> <?php } else { ?><p class="bcz-no-data-msg h5">No new opportunity.</p> <?php } ?>
    
    
    
    
    </div> 
    <div class="tab-pane " id="New_Accounts">
    
    
    
        <?php if ($newcompanys[0]) { 
		
		$this->load->view('DataTableViewOpp', array('className'=>'home_comy','cols'=>$Ccols,'sourcePath' => 'dashboard/getnewcompanysjsno')); ?>
		
    
    <?php /*?><table class="table table-striped m-b-none bcz-data-table">
    <thead>
    <th>Name</th>
    
    <th>Owner</th>
    <th>Type</th>
    <th>Phone</th>

    </thead>
    <tbody>
    <?php
	
	foreach ($newcompanys as $newcompany) { ?>
    
    <div class="media-body">
    <tr> <td class="task_tital">
   
        
          <a title="<?=$newcompany->company_name;?>" href="<?=base_url()?>companies/details/<?=$newcompany->company_id?>" ><?=substr($newcompany->company_name, 0,18)?></a>
        
     
      </td>
      
      <td class="task_company"><small class="block m-b-none"><?=$newcompany->name?></small></td>
      
      <td class="task_company"><small class="block m-b-none"><?=$newcompany->com_cust_type?></small></td>
      
       
      
        <td class="task_priority"><small class="block m-b-none"><?=$newcompany->phone?></small></td>
        
      
        
          
      </tr>
      </div>
    
        
        
  
    <? } ?></tbody></table><?php */?> <?php } else { ?><p class="bcz-no-data-msg h5">No new account.</p> <?php } ?>
    
    
    </div> 

    </div>
   
    </section>