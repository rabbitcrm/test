<!-- nav -->
<nav id="nav" class="nav-primary hidden-xs">
  <ul class="nav" data-spy="affix" data-offset-top="50">
     <li class="dropdown-submenu<?=in_array($this->bodyClass, array('Home'))?' active':''?>">
      <a href="<?=base_url()?>home"><i class="icon-home icon-xlarge"></i><span>Home</span></a>
    </li>
     <li class="dropdown-submenu<?=in_array($this->bodyClass, array('Calendar'))?' active':''?>">
      <a href="<?=base_url()?>calendar"><i class="icon-calendar icon-xlarge"></i><span style=" margin-left: -4px;">Calendar</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('campaign', 'create-campaign', 'campaign-details', 'edit-campaign'))?' active':''?>">
      <a href="<?=base_url()?>campaign"><i class="icon icon-bullhorn icon-xlarge "></i><span style=" margin-left: -4px;">Campaigns</span></a>
    </li>
    
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('leads', 'create-lead', 'lead-details', 'edit-lead','LeadsImportView','LeadsImportMappingView'))?' active':''?>">
      <a href="<?=base_url()?>leads"><i class="icon-lightbulb icon-xlarge m-r-mini m-l-mini "></i><span>Leads</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('Opportunities', 'create-deal', 'deal-details', 'edit-deal'))?' active':''?>">
      <a href="<?=base_url()?>deals"><i class="icon-thumbs-up-alt icon-xlarge"></i><span>Opportunities</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('tasks', 'create-task', 'task-details', 'edit-task'))?' active':''?>">
      <a href="<?=base_url()?>tasks"><i class="icon-check icon-xlarge"></i><span>Tasks</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('contacts', 'create-contact', 'contact-details', 'edit-contact','ContactsImportView','ContactsImportMappingView'))?' active':''?>">
      <a href="<?=base_url()?>contacts"><i class="icon-group icon-large"></i><span>Contacts</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('Accounts', 'create-company', 'company-details', 'edit-company','CompaniesImportView','CompaniesImportMappingView'))?' active':''?>">
      <a href="<?=base_url()?>companies"><i class="icon-building icon-xlarge"></i><span>Accounts</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('products', 'create-product', 'product-details', 'edit-product','ProductsImportView','ProductsImportMappingView'))?' active':''?>">
      <a href="<?=base_url()?>products"><i class="icon-hdd icon-xlarge"></i><span>Products</span></a>
    </li>
    <li <?php if (in_array($this->bodyClass, array('quotes', 'create-quote', 'quote-details', 'edit-quote'))) { ?>class="active"<?php } ?>>
      <a href="<?=base_url()?>quotes"><i class="icon-edit icon-xlarge"></i><span>Quotes</span></a>
    </li>
    
    <li <?php if (in_array($this->bodyClass, array('orders', 'create-order', 'order-details', 'edit-order'))) { ?>class="active"<?php } ?>>
      <a href="<?=base_url()?>orders"><i class="icon-briefcase icon-xlarge"></i><span>Sales Orders</span></a>
    </li>
    <li class="dropdown-submenu<?=in_array($this->bodyClass, array('cases', 'create-case', 'case-details', 'edit-case'))?' active':''?>">
      <a href="<?=base_url()?>cases"><i class="icon-ticket icon-xlarge"></i><span>Tickets</span></a>
    </li>
    <li <?php if ($this->bodyClass == 'docs') { ?>class="active"<?php } ?>>
      <a href="<?=base_url()?>docs"><i class="icon-folder-open-alt icon-xlarge"></i><span>Documents</span></a>
    </li>
  </ul>
</nav>
<!-- / nav -->