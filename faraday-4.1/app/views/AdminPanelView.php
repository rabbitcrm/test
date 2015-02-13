<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="en" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>      <html lang="en" class="no-js lt-ie9 lt-ie8">    <![endif]-->
<!--[if IE 8]>      <html lang="en" class="no-js lt-ie9">         <![endif]-->
<html lang="en" class="no-js">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title> :: SkyzonCRM<?=($this->pageTitle ? ' | '.ucfirst($this->pageTitle) : '')?> :: </title>
    <meta name="description" content="<?=$this->pageDesc;?>">
    <meta name="author" content="Siva Durgarao">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Template Styles -->
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/bootstrap.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/font.css" cache="false">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/plugin.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/css/first/style.css">
    <link type="text/css" rel="stylesheet" href="<?=base_url()?>assets/js/first/select2/select2.css" cache="false">

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
      <header id="header" class="navbar bg bg-black">
        <!-- <a href="<?=base_url()?>user/add" class="btn btn-link pull-right m-t-mini text-danger">Sign up</a> -->
        <a class="navbar-brand no-padder" href="<?=base_url()?>"><span class="navbar-logo-wrapper"><img src="<?=($_SESSION['bcz_org_logo']?$_SESSION['bcz_org_logo']:(base_url().'assets/img/logo.jpg'))?>" width="" height="40" border="0" title="Skyzon" alt="" /></span></a>
      </header>
      <!-- / header -->

      <section id="content">
        <div class="main padder">
          <div class="clearfix">
            <h3 class="disp-i-b">Admin Statistics</h3>
          </div>

          <div class="panel" data-org-user-count="<?=count($users)?>">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseOne', 'headingText' => 'Organizations Info', 'actionButtons' => false)); ?>

            <div id="collapseOne" class="panel-collapse in">
              <div class="panel-body text-small" id="orgs_container">
                <?php if ($organizations[0]) { ?>
                  <div class="h4 m-b-large">Total Organizations: <?=count($organizations)?></div>
                  <div class="h5 m-b-small"><strong>Organization Users:</strong></div>
                  <?php $this->load->view('OrganizationsListingView'); ?>
                <?php } else { ?>
                  <div class="h5">No organizations found.</div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Users Information', 'actionButtons' => false)); ?>

            <div id="collapseTwo" class="panel-collapse collapse in">
              <div class="panel-body text-small" id="users_container">
                <?php if ($users[0]) { ?>
                  <div class="h4 m-b-large">Total Users: <?=count($users)?></div>
                  <div class="h5 m-b-small">Admins: <?=$adminCnt?></div>
                  <div class="h5 m-b-small">Managers: <?=$managerCnt?></div>
                  <div class="h5 m-b-small">Executives: <?=$executiveCnt?></div>

                  <div class="h5 m-b-small m-t-large "><strong>Active Users:</strong></div>
                  <div class="h5 m-b-small">Today: <?=$active_users_day->users?></div>
                  <div class="h5 m-b-small">This Week: <?=$active_users_week->users?></div>
                  <div class="h5 m-b-small">This Month: <?=$active_users_month->users?></div>

                <?php } else { ?>
                  <div class="h5">No users found.</div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseThree', 'headingText' => 'Deals Information', 'actionButtons' => false)); ?>

            <div id="collapseThree" class="panel-collapse collapse in">
              <div class="panel-body text-small" id="deals_container">
                <?php if ($deals[0]) { ?>
                  <div class="h4 m-b-large">Total Deals: <?=count($deals)?></div>
                  <div class="h5 m-b-small">Total Deals Value: <?=$dealAmt?></div>
                  <div class="h5 m-b-small">Won Deals: <?=$wonDeals?></div>
                  <div class="h5 m-b-small">Won Deals Value: <?=$wonDealsAmt?></div>
                <?php } else { ?>
                  <div class="h5">No deals found.</div>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </section>

      <?php $this->load->view("FooterView"); ?>
      
    <!-- Template Scripts -->
    <script src="<?=base_url()?>assets/js/first/jquery.min.js"></script>
    <script src="<?=base_url()?>assets/js/first/bootstrap.js"></script>

    <!-- datatables -->
    <script src="<?=base_url()?>assets/js/first/datatables/jquery.dataTables.min.js"></script>

    <script src="<?=base_url()?>assets/js/first/app.js"></script>
    <script src="<?=base_url()?>assets/js/first/app.plugin.js"></script>
    <script src="<?=base_url()?>assets/js/first/app.data.js"></script>
      
    <!-- parsley -->
    <script src="<?=base_url()?>assets/js/first/parsley/parsley.min.js"></script>
    <!-- select2 -->
    <script src="<?=base_url()?>assets/js/first/select2/select2.min.js"></script>

    <!-- Application Scripts -->
    <script type="text/javascript" src="<?=base_url()?>assets/js/BCZScripts.js"></script>
  </body>
</html>