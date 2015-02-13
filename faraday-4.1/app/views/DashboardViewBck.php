<div class="row">
    <div class="col-lg-12">
      <section class="toolbar clearfix m-t-large m-b">
        <a href="<?=base_url()?>campaign" class="btn btn-primary btn-circle"><i class="icon-lightbulb"></i>Campaign</a>
        
        <a href="<?=base_url()?>leads" class="btn btn-primary btn-circle"><i class="icon-lightbulb"></i>Leads</a>
        <a href="<?=base_url()?>deals" class="btn btn-inverse btn-circle"><i class="icon-thumbs-up-alt"></i>Deals</a>
        <a href="<?=base_url()?>tasks" class="btn btn-success btn-circle"><i class="icon-check"></i>Tasks</a>
        <a href="<?=base_url()?>quotes" class="btn btn-info btn-circle"><i class="icon-edit"></i>Quotes</a>
        <a href="<?=base_url()?>orders" class="btn btn-danger btn-circle"><i class="icon-briefcase"></i>Orders</a>
        <a href="<?=base_url()?>cases" class="btn btn-warning btn-circle"><i class="icon-ticket"></i>Tickets</a>

        <section class="add-item inline">
          <button class="dropdown-toggle btn btn-circle" data-toggle="dropdown"><i class="icon-plus"></i>Add</button>

          <ul class="dropdown-menu">
           <li><a href="<?=base_url()?>campaign/add"><i class="icon-lightbulb"></i>Campaign</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>leads/add"><i class="icon-lightbulb"></i>Lead</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>deals/add"><i class="icon-thumbs-up-alt"></i>Deal</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>tasks/add"><i class="icon-check"></i>Task</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>contacts/add"><i class="icon-book"></i>Contact</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>companies/add"><i class="icon-building"></i>Company</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>products/add"><i class="icon-hdd"></i>Product</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>cases/add"><i class="icon-ticket"></i>Ticket</a></li>
          </ul>
        </section>
      </section>
    </div>

    <div class="col-lg-6">
      <div class="row">
        <!-- easypiechart -->
        <div class="col-xs-6">
          <section class="panel">
            <header class="panel-heading bg-white">
              <div class="text-center h5">This Year</div>
            </header>
            <div class="panel-body pull-in text-center">
              <div class="inline">
                <div class="easypiechart" data-percent="<?=$sales['yearPercentage']?>" data-bar-color="#5191d1">
                  <span class="h2" style="margin-left:10px;margin-top:10px;display:inline-block"><?=$sales['yearPercentage']?$sales['yearPercentage']:0?></span>%
                  <div class="easypie-text text-muted h5">Sales</div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="col-xs-6">
          <section class="panel">
            <header class="panel-heading bg-white">
              <div class="text-center h5">This Month</div>
            </header>
            <div class="panel-body pull-in text-center">
              <div class="inline">
                <div class="easypiechart" data-percent="<?=$sales['monthPercentage']?>" data-bar-color="#13c4a5">
                  <span class="h2" style="margin-left:10px;margin-top:10px;display:inline-block"><?=$sales['monthPercentage']?$sales['monthPercentage']:0?></span>%
                  <div class="easypie-text text-muted h5">Sales</div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <!-- easypiechart end-->
      </div>

      <section class="panel" style="margin-top: 2px;">
        <div class="panel-body text-muted l-h-2x">
          <span class="h4 m-r text-warning">Sales:</span>
          <span class="badge"><?=$sales['total']?$sales['total']:0?></span>
          <span class="m-r-small">Total</span>
          <span class="badge bg-info"><?=$sales['year']?$sales['year']:0?></span>
          <span class="m-r-small">This Year</span>
          <span class="badge bg-primary"><?=$sales['month']?$sales['month']:0?></span>
          <span class="m-r-small">This Month</span>
        </div>
      </section>
    </div>

    <div class="col-lg-6"><?php $this->load->view('SnapshotView'); ?></div> 
</div>

<div class="row">
  <div class="col-lg-6"><?php $this->load->view('DealsListView'); ?></div>
	<div class="col-lg-6"><?php $this->load->view('CasesListView'); ?></div>
</div>

<div class="row">
  <div class="col-lg-6">
    <h3>Funnel Chart</h3>
    <div id="funnel_chart"></div>
  </div>
</div>