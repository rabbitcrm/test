<!DOCTYPE html>
<!--[if lt IE 7]>	<html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7">	<![endif]-->
<!--[if IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8"> 		<![endif]-->
<!--[if IE 8]>      <html lang="en" class="no-js lt-ie9"> 				<![endif]-->
<html lang="en" class="no-js">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	  
		<title><?=$this->supportInfo['name']?><?=($this->pageTitle ? ' | '.ucfirst($this->pageTitle) : '')?>  </title>
		<meta name="description" content="<?=$this->pageDesc;?>">
		<meta name="author" content="Siva Durgarao">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- Template Styles -->
		<link rel="icon" href="<?=base_url()?>assets/img/favicon.png" type="image/png" sizes="16x16">
		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/bootstrap.css">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/font-awesome.min.css">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/font.css" cache="false">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/js/first/select2/select2.css" cache="false">
		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/style.css">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/plugin.css">
  		<link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/landing.css">


    <?php 
	if($this->bodyClass=='Calendar')
		{
			?>

    <!-- Tc core CSS -->
	<link id="qstyle" rel="stylesheet" href="<?=base_url()?>assets/css/calendar/style.css">

<?php } ?>
		<!-- Previous Styles
		<link type="text/css" href="<?=base_url()?>assets/css/main.css" rel="stylesheet" />
		-->

      <!-- Ignite UI Required Combined CSS Files -->
    <link href="<?=base_url()?>assets/css/infragistics.theme.css" rel="stylesheet" />
    <link href="<?=base_url()?>assets/css/infragistics.css" rel="stylesheet" />

  		<!-- Application Styles -->
	    <link type="text/css" href="<?=base_url()?>assets/plugins/autocomplete/jquery.autocomplete.css" rel="stylesheet" />
	    <link type="text/css" href="<?=base_url()?>assets/css/BCZStyles.css" rel="stylesheet" />

<link type="text/css" href="<?=base_url()?>assets/css/update-styless.css" rel="stylesheet" />

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
	
	<body class="bclouds navbar-fixed <?=$this->bodyClass;?>">
		<?php $this->load->view("HeaderView"); ?>
		<?php $this->load->view("NavigationView"); ?>

		<section id="content">
			<section class="main padder">
				<?php $this->load->view($content); ?>
			</section>

			<?php $this->load->view("FooterView"); ?>
		</section>
		
		<?php $this->load->view("ModalsHolderView"); ?>
  		
		<!-- Template Scripts -->
		<script src="<?=base_url()?>assets/js/first/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/js/first/jquery-ui.min.js"></script>
		<script src="<?=base_url()?>assets/plugins/jquery.form.min.js"></script>

        <script type="text/javascript" src="<?=base_url()?>assets/plugins/autocomplete/jquery.autocomplete.min.js"></script>

  		<script src="<?=base_url()?>assets/js/first/bootstrap.js"></script>
        
        <script type="text/javascript" src="<?=base_url()?>assets/js/BCZScripts.js"></script>
  		
        <?php if(full_url()=='dashboard')
		{ ?>
        <script>
		$(document).ready(function() {
			
			$('#share_btn').click(function()
			{
				
				var share=$('#share');
				var error=$('#error');
				var mes='<div class="alert ta-left alert-danger">
		<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
					<p><i class="icon-ban-circle icon-large m-r"></i></p><p>Enter The Text.</p><p></p></div>');
					
					if(share.val()=="")
					{
						share.focus();
						error.html(mes);
						return false;
					}
					else
					{
						error.html('');
					}
			});
            
        });
		
		</script>
        
        
		<!-- Chart -->
 		<?php $this->load->view("chart"); }
		  ?>
        <script type="text/javascript">
function showimagepreview(input) {
if (input.files && input.files[0]) {
var filerdr = new FileReader();
filerdr.onload = function(e) {
$('#imgprvw').attr('src', e.target.result);
}
filerdr.readAsDataURL(input.files[0]);
}
}
</script>
        <?php  ?>
 
		<!-- Sparkline Chart -->
		<script src="<?=base_url()?>assets/js/first/charts/sparkline/jquery.sparkline.min.js"></script>  
		<!-- Easy Pie Chart -->
		<script src="<?=base_url()?>assets/js/first/charts/easypiechart/jquery.easy-pie-chart.js"></script>  
	  	<!-- datatables -->
	  		  	          <?php if($this->bodyClass=='Home')
		{
			?>
	  	<!-- datatables -->
        <script src="<?=base_url()?>assets/js/first/datatables/jquery.home.dataTables.min.js"></script>
        <?php } else { ?>
        <!-- datatables -->
	  	<script src="<?=base_url()?>assets/js/first/datatables/jquery.dataTables.min.js"></script>
        
        <?php } ?>
		<!-- fuelux -->
		<script src="<?=base_url()?>assets/js/first/fuelux/fuelux.js"></script>
		<script src="<?=base_url()?>assets/js/first/underscore-min.js"></script>
	  	<!-- parsley -->
	  	<script src="<?=base_url()?>assets/js/first/parsley/parsley.min.js"></script>
	  	<!-- datepicker -->
	  	<script src="<?=base_url()?>assets/js/first/datepicker/bootstrap-datepicker.js"></script>
	  	<!-- select2 -->
	  	<script src="<?=base_url()?>assets/js/first/select2/select2.min.js"></script>
	  	<!-- file input -->  
	  	<script src="<?=base_url()?>assets/js/first/file-input/bootstrap.file-input.js"></script>
  	  <!-- Flot -->
		  <script src="<?=base_url()?>assets/js/first/charts/flot/jquery.flot.min.js" cache="false"></script>
		  <script src="<?=base_url()?>assets/js/first/charts/flot/jquery.flot.categories.js" cache="false"></script>

  		<script src="<?=base_url()?>assets/js/first/app.js"></script>
  		<script src="<?=base_url()?>assets/js/first/app.plugin.js"></script>
  		<script src="<?=base_url()?>assets/js/first/app.data.js"></script>
        
        

    <!-- Ignite UI Required Combined JavaScript Files -->
    <script src="<?=base_url()?>assets/js/infragistics.core.js"></script>
    <script src="<?=base_url()?>assets/js/infragistics.dv.js"></script>
    <script src="<?=base_url()?>assets/js/infragistics.lob.js" type="text/javascript"></script>
           <?php 
	if($this->bodyClass=='Calendar')
		{
			$this->load->view("JSCalendar"); 
 
		}
 
	if($this->bodyClass=='advancedsettings')
		{
			?>
             <script src="<?=base_url()?>assets/js/editer.js" type="text/javascript"></script>
            <?php
		}
				   if(in_array($this->bodyClass, array('create-task', 'edit-task','lead-details','deal-details','contact-details','company-details','case-details','campaign-details'))){
		  ?>
		  <link rel="stylesheet" href="<?=base_url()?>assets/css/default.min.css" />
    <script src="<?=base_url()?>assets/js/bootstrap.timepicker.min.js"></script>
		  
		  <?php } ?>

		<!-- Application Scripts -->
        		<!-- Application Scripts -->
        <?php if($this->bodyClass=='Home')
		{
			?>
     

 <script type="text/javascript" language="javascript" src="<?=base_url()?>assets/js/Pager/bootstrapPager.min.js"></script>

<?php } ?>



<?php

function full_url()
{
$router =& load_class('Router', 'core');
$c=$router->fetch_class();
   return $c;
}
?>
	    
	  


	     
	   
	</body>
</html>