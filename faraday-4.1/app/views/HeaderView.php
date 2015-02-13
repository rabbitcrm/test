<!-- header -->
<header id="header" class="navbar">
  <ul class="nav navbar-nav navbar-avatar pull-right">
   <?php if ($this->isAdmin) { ?>
        
        
         <?php
		 $con=0;
		  if($this->user->pcode!="")
		{
			$con++;
			
		}
		if($this->user->demo=="0")
		{
			$con++;
			
		}
		
		if($con!=0)
		{
			?>
            
            
      <li class="dropdown dropdown_notifications" style="top: 10px;">
       <a href="#" class="dropdown-toggle dk" data-toggle="dropdown"> <i class="icon-bell icon-xlarge m-r-mini m-l-mini"></i> 
       <span class="badge badge-sm up bg-danger m-l-n-sm count" style="display: inline-block;"><?=$con?></span> 
       </a> 
       <section class="dropdown-menu aside-xl notifications">
        <section class="panel bg-white"> 
        <header class="panel-heading b-light bg-light"> 
        <strong>You have <span class="count" style="display: inline;"><?=$con?></span> notifications</strong> </header> <div class="list-group list-group-alt animated fadeInRight">
        <?php if($this->user->pcode!="")
		{
			?>
        <a href="#" class="media list-group-item" style="display: block;">Set Your Profile Using Header Dropdown Menu</a>
        <?php } ?>
        
        
         <?php if($this->user->demo=="0")
		{
			?>
         <a href="#" class="media list-group-item"> <span class="pull-left thumb-sm">  </span> <span class="media-body block m-b-none">Clear Demo Data Using Header Dropdown Menu </span> </a> 
         
          <?php } ?>
         <a href="#" class="media list-group-item">  </a> </div> </section> </section> </li>
        <?php } } ?>
       
    <li class="dropdown">
      <a href="" class="dropdown-toggle" data-toggle="dropdown">            
        <span class="hidden-xs-only"><?=$this->user->name?ucfirst($this->user->name):$this->user->username?></span>
        <span class="thumb-small avatar inline"><img src="<?=$this->user->profile_pic?$this->user->profile_pic:(base_url().'assets/img/default-profile-pic.png')?>" alt="Mika Sokeil" class="img-circle" /></span>
        <b class="caret hidden-xs-only"></b>
      </a>
      <ul class="dropdown-menu ">
        <li><a href="<?=base_url()?>profile"><i class="icon-user"></i><?=$this->isAdmin?'Profile':'Profile'?></a></li>
        <?php if ($this->isAdmin) { ?><li><a href="<?=base_url()?>settings"><i class="icon-cog"></i>Settings</a></li><?php } ?>
        <?php if ($this->isAdmin) { ?><li><a href="<?=base_url()?>advancedsettings"><i class="icon-cogs"></i>Advanced Settings</a></li><?php } ?>
        <li class="divider"></li>
        <li><a href="<?=base_url()?>logout"><i class="icon-signout"></i>Logout</a></li>
        <li class="divider"></li>
        <li><a href="<?=base_url()?>help"><i class="icon-info-sign"></i>Help / Support</a></li>
        
        <?php if ($this->isAdmin && $this->user->demo==0) { ?>
        
          <li class="divider"></li>
        <li><a href="<?=base_url()?>demo"><i class="icon-trash"></i>Delete Demo Data</a></li>
        <?php } ?>
        
      </ul>
     
    </li>
  </ul>
  
 
  
  <a class="navbar-brand no-padder" href="<?=base_url()?>"><span class="navbar-logo-wrapper"><img src="<?=$_SESSION['bcz_org_logo']?$_SESSION['bcz_org_logo']:base_url().'assets/img/home_logo.jpg'?>" width="139" height="" border="0" title="<?=$this->supportInfo['name']?>" alt="" <?php if(!$_SESSION['bcz_org_logo']) { ?>style="margin-top: 8px;"<?php } ?> /></span></a>
  <button type="button" class="btn btn-link pull-left nav-toggle visible-xs" data-toggle="class:slide-nav slide-nav-left" data-target="body">
    <i class="icon-reorder icon-xlarge text-default"></i>
  </button>

  <ul class="nav navbar-nav hidden-xs">
    <li>
      <div class="m-t-small">
        <button class="dropdown-toggle btn btn-sm btn-inverse" data-toggle="dropdown"><i class="icon-plus"></i> ADD</button>

        <ul class="dropdown-menu">
         <li><a href="<?=base_url()?>campaign/add"><i class="icon-bullhorn"></i>Campaign</a></li>
         <li class="divider"></li>
          <li><a href="<?=base_url()?>leads/add"><i class="icon-lightbulb"></i>Lead</a></li>
          <li class="divider"></li>
          <li><a href="<?=base_url()?>deals/add"><i class="icon-thumbs-up-alt"></i>Opportunity</a></li>
          <li class="divider"></li>
          <li><a href="<?=base_url()?>tasks/add"><i class="icon-check"></i>Task</a></li>
          <li class="divider"></li>
          <li><a href="<?=base_url()?>contacts/add"><i class="icon-group"></i>Contact</a></li>
          <li class="divider"></li>
          <li><a href="<?=base_url()?>companies/add"><i class="icon-building"></i>Account</a></li>
          <li class="divider"></li>
          <li><a href="<?=base_url()?>products/add"><i class="icon-hdd"></i>Product</a></li>
          <li class="divider"></li>
          <li><a href="<?=base_url()?>cases/add"><i class="icon-ticket"></i>Ticket</a></li>
        </ul>
      </div>
    </li>
  </ul>

  <form class="navbar-form pull-left shift bcz-search-form" action="<?=base_url()?>search" data-toggle="shift:appendTo" data-target=".nav-primary">
    <i class="icon-search text-muted"></i>
    <input id="bcz_search" name="q" type="text" class="input-sm form-control dropdown-toggle" placeholder="Search" data-toggle="dropdown" value="" autocomplete="off" >
    <ul class="dropdown-menu hide"></ul>
  </form>
</header>
<!-- / header -->