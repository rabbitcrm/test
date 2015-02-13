<?php



 if ($associated) { ?>
	<div class="col-sm-8 text-center no-padder-v m-t-mini">
  	<div class="h4 col-sm-6 text-left m-t-small m-b-small">Type</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($task->associate_to?ucfirst($task->associate_to):$this->noDataChar)?></div>
  	<?php if ($associatedType == 'company') { ?>
    	<div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($associated->company_name?"<a class='text-primary' href='".base_url()."companies/details/$associated->company_id'>".$associated->company_name."</a>":$this->noDataChar)?></div>
    	<div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->company_create_date?convertDateTime($associated->company_create_date):$this->noDataChar)?></div>
    	<div class="h4 col-sm-6 text-left m-b-small">Customer Type</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->com_cust_type?$associated->com_cust_type:$this->noDataChar)?></div>
    	<div class="h4 col-sm-6 text-left m-b-small">Employees</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->com_employees?$associated->com_employees:$this->noDataChar)?></div>
    	<div class="h4 col-sm-6 text-left m-b-small">Revenue</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->com_revenue?$associated->com_revenue:$this->noDataChar)?></div>
  	<?php } else if ($associatedType == 'contact') { $contact_name = $associated->first_name . ($associated->last_name ? " $associated->last_name" : ''); ?>
      <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($contact_name?"<a class='text-primary' href='".base_url()."contacts/details/$associated->contact_id'>".$contact_name."</a>":$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->contact_create_date?convertDateTime($associated->contact_create_date):$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Designation</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->designation?$associated->designation:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Phone</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->mobile?$associated->mobile:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Email</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->email?$associated->email:$this->noDataChar)?></div>
  	<?php } else if ($associatedType == 'opportunity') { ?>
      <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($associated->deal_name?"<a class='text-primary' href='".base_url()."deals/details/$associated->deal_id'>".$associated->deal_name."</a>":$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->deal_create_date?convertDateTime($associated->deal_create_date):$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Stage</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->stage?$associated->stage:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Status</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->status?$associated->status:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Amount</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->deal_amount?$associated->deal_amount:$this->noDataChar)?></div>
  	<?php } else if ($associatedType == 'lead') { $lead_name = $associated->first_name . ($associated->last_name ? " $associated->last_name" : ''); ?>
      <div class="h4 col-sm-6 text-left m-t-small m-b-small">Name</div><div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($lead_name?"<a class='text-primary' href='".base_url()."leads/details/$associated->lead_id'>".$lead_name."</a>":$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->lead_create_date?convertDateTime($associated->lead_create_date):$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Account</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->company_name?$associated->company_name:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Phone</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->mobile?$associated->mobile:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Email</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->email?$associated->email:$this->noDataChar)?></div>
  	<?php } else if ($associatedType == 'campaign') {
		
		
		 $campaign_name = $associated->campaign_name; ?>
      <div class="h4 col-sm-6 text-left m-t-small m-b-small">
      Name</div>
      <div class="h4 col-sm-6 text-left m-t-small m-b-small"><?=($campaign_name?"<a class='text-primary' href='".base_url()."campaign/details/".$associated->campaign_id."'>".$campaign_name."</a>":$this->noDataChar)?></div>
      
      <div class="h4 col-sm-6 text-left m-b-small">Target Audience</div>
      
      <div class="h4 col-sm-6 text-left m-b-small"><?=$associated->target_audience;?></div>
      
     
      
      <div class="h4 col-sm-6 text-left m-b-small">Created On</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->create_date?convertDateTime($associated->create_date):$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Product</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->product?$associated->product:$this->noDataChar)?></div>
      <div class="h4 col-sm-6 text-left m-b-small">Close Date</div><div class="h4 col-sm-6 text-left m-b-small"><?=($associated->closedate?convertDateTime($associated->closedate):$this->noDataChar)?></div>
      
  	<?php } ?>
	</div>
<?php } else { ?>
  <p class="bcz-no-data-msg h5">No data found.</p>
<?php } ?>