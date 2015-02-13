<!DOCTYPE html>
<!--[if lt IE 7]>	<html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7">	<![endif]-->
<!--[if IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8"> 		<![endif]-->
<!--[if IE 8]>      <html lang="en" class="no-js lt-ie9"> 				<![endif]-->
<html lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title> <?=$this->supportInfo['name']?><?=($this->pageTitle ? ' | '.ucfirst($this->pageTitle) : '')?> </title>
		<meta name="description" content="<?=$this->pageDesc;?>">
		<meta name="author" content="Siva Durgarao">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Template Styles -->
		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/bootstrap.css">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/font-awesome.min.css">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/font.css" cache="false">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/plugin.css">
		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/style.css">

  		<!-- Application Styles -->
	    <link type="text/css" href="<?=base_url()?>assets/css/BCZStyles.css" rel="stylesheet" />

	  	<!--[if lt IE 9]>
	    <script type="text/javascript" src="<?=base_url()?>assets/js/first/ie/respond.min.js" cache="false"></script>
	    <script type="text/javascript" src="<?=base_url()?>assets/js/first/ie/html5.js" cache="false"></script>
	    <script type="text/javascript" src="<?=base_url()?>assets/js/first/ie/excanvas.js" cache="false"></script>
	  	<![endif]-->

		<!-- Global Variables Definition -->
	    <script type="text/javascript">
	    	// Global variables
			var appBaseUrl = "<?=base_url()?>";
	    </script>
	</head>
	
	<body class="bclouds <?=$this->bodyClass;?>">
	  	<!-- header -->
	  	<header id="header" class="">
		    <!-- <a href="<?=base_url()?>user/add" class="btn btn-link pull-right m-t-mini text-danger">Sign up</a> -->
  			<a class=" no-padder" href="http://rabbitcrm.com"><span class="navbar-logo-wrapper"><img src="<?=($_SESSION['bcz_org_logo']?$_SESSION['bcz_org_logo']:(base_url().'assets/img/logo.jpg'))?>" width="" height="40" border="0" title="<?=$this->supportInfo['name']?>" alt="" /></span></a>
	  	</header>
	  	<!-- / header -->

	  	<section id="content">
		    <div class="main padder">
		      <div class="row m-t-large">
		        <div class="col-sm-6 col-sm-offset-3 m-t-large" >
		          <section class="panel">
		            <header class="panel-heading text-center h4" style="color:#000000;">
		            Change Password
		            </header>
		            <div class="panel-body">

	            	  <? $this->load->view('MessagesView', array(messages => $messages)) ?>

		              <form action="<?=base_url()?>reset/change" class="form-horizontal" method="post" data-validate="parsley">
		       
		                <div class="block">
		                 <div class="panel-body">
                    
                    <div class="form-group">
                      <label class="col-lg-3 control-label" style="text-align: left !important;">New Password</label>
                      <div class="col-lg-6">
                        <input type="password" name="password" class="form-control" data-required="true" >
                      </div>
                    </div>
                    <div class="form-group m-b-none">
                      <label class="col-lg-3 control-label"  style="text-align: left !important;">Confirm Password</label>
                      <div class="col-lg-6">
                        <input type="password" name="conf_password" class="form-control" data-required="true" >
                      </div>
                    </div>
                  </div>
		                </div>

		             <?php /*?> 	<a href="<?=base_url()?>login" class="pull-right m-t-mini"><small>Sign in</small></a><?php */?>
		              	<button type="submit" class="btn btn-info col-sm-offset-3 m-t-large">Update</button>
		              </form>
		            </div>
		          </section>
		        </div>
		      </div>
	    </div>
	  	</section>

	  	<?php $this->load->view("FooterView"); ?>
  		
		<!-- Template Scripts -->
		<script src="<?=base_url()?>assets/js/first/jquery.min.js"></script>
		<script src="<?=base_url()?>assets/js/first/bootstrap.js"></script>

		<script src="<?=base_url()?>assets/js/first/app.js"></script>
		<script src="<?=base_url()?>assets/js/first/app.plugin.js"></script>
		<script src="<?=base_url()?>assets/js/first/app.data.js"></script>
	  	
	  	<!-- parsley -->
	  	<script src="<?=base_url()?>assets/js/first/parsley/parsley.min.js"></script>

		<!-- Application Scripts -->
	    <script type="text/javascript" src="<?=base_url()?>assets/js/BCZScripts.js"></script>
	</body>
</html>