<div class="row">
    <div class="col-lg-12">
      <section class="toolbar clearfix m-t-large m-b">
        <a href="<?=base_url()?>campaign" class="btn btn-facebook btn-circle"><i class="icon-bullhorn"></i>Campaign</a>
        
        <a href="<?=base_url()?>leads" class="btn btn-primary btn-circle" title="Lead"><i class="icon-lightbulb"></i>Leads</a>
        <a href="<?=base_url()?>deals" title="Opportunities" class="btn btn-inverse btn-circle"><i class="icon-thumbs-up-alt"></i>Opportunities</a>
        <a href="<?=base_url()?>tasks" title="Tasks" class="btn btn-success btn-circle"><i class="icon-check"></i>Tasks</a>
        <a href="<?=base_url()?>quotes" title="Quotes" class="btn btn-info btn-circle"><i class="icon-edit"></i>Quotes</a>
        <a href="<?=base_url()?>orders" title="Sales Orders" class="btn btn-danger btn-circle"><i class="icon-briefcase"></i>Sales Orders</a>
        <a href="<?=base_url()?>cases" title="Tickets" class="btn btn-warning btn-circle"><i class="icon-ticket"></i>Tickets</a>

        <section class="add-item inline">
          <button class="dropdown-toggle btn btn-circle" data-toggle="dropdown"><i class="icon-plus"></i>Add</button>

          <ul class="dropdown-menu">
          <li><a href="<?=base_url()?>campaign/add"><i class="icon-bullhorn"></i>Campaign</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>leads/add" title="Lead"><i class="icon-lightbulb"></i>Lead</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>deals/add" title="Opportunity"><i class="icon-thumbs-up-alt"></i>Opportunity</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>tasks/add" title="Task"><i class="icon-check"></i>Task</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>contacts/add" title="Contact"><i class="icon-group"></i>Contact</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>companies/add" title="Account"><i class="icon-building"></i>Account</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>products/add" title="Product"><i class="icon-hdd"></i>Product</a></li>
            <li class="divider"></li>
            <li><a href="<?=base_url()?>cases/add" title="Ticket"><i class="icon-ticket"></i>Ticket</a></li>
          </ul>
        </section>
      </section>
    </div>

    <div class="col-lg-12 m-t-large m-b-large">
    <div id="error"></div>
      <? $this->load->view('MessagesView', array(messages => $messages)) ?>
      <section class="panel panel-info">
        <form class="form-horizontal m-b-none" method="post" data-validate="parsley" action="<?=base_url()?>dashboard" accept-charset="utf-8" enctype="multipart/form-data">
          <textarea name="post_body" class="form-control no-border" id="share" rows="2" placeholder="Share something..."  required="required" ></textarea>
          <footer class="panel-footer bg-light">
            <button class="btn btn-info pull-right btn-sm font-bold" id="share_btn">SHARE</button>
            <ul class="nav nav-pills nav-sm">
              <li>
                <div class="media-body">
                  <input type="file" name="post_file" title="Choose file" class="btn btn-sm btn-info"><br>
                </div>
              </li>
            </ul>
          </footer>
        </form>
      </section>
    </div>

<div class="row m-t-large m-b-large">
<div class="col-lg-7 marginlr">

<section class="panel "> <div class="carousel slide " id="c-slide"> <ol class="carousel-indicators out"> <li data-target="#c-slide" data-slide-to="0" class=""></li> <li data-target="#c-slide" data-slide-to="1" class="active"></li> <li data-target="#c-slide" data-slide-to="2" class=""></li> </ol> <div class="carousel-inner"> 




<div class="item active"> <p class="text-center"> <em class="h4 text-mute">Sales Pipeline</em></p> <div id="sales_pipeline" style="width: 90%; min-height: 250px; background-color: #fff;"></div> </div>


<div class="item "> <p class="text-center"> <em class="h4 text-mute">Leads By Source Campaign</em> </p>  <div id="container" style="min-height: 250px; background-color: #fff;"></div> </div> 




 <div class="item"> <p class="text-center"> <em class="h4 text-mute">Tickets By Priority</em></p> <div id="cases_priority1" style="min-height: 250px; background-color: #fff;"></div>  </div> 



 
 </div> <a class="left carousel-control" href="#c-slide" data-slide="prev"> <i class="icon-chevron-left"></i> </a> <a class="right carousel-control" href="#c-slide" data-slide="next"> <i class="icon-chevron-right"></i> </a> </div> </section>

</div>




<div class="col-lg-5 no-padder">
<div class="col-lg-12 marginlr" ><?php $this->load->view('Newinbox'); ?></div>
  </div>
  
  
  </div>

<div class="row m-t-large m-b-large">
  <div class="col-lg-7 no-padder-r1 marginlr">
<!-- Start Task  -->
  <div class="col-lg-12 no-padder"><?php $this->load->view('TaskNotesView'); ?></div>
  

  
  
  
  
<!-- End Task  -->
    <div class="col-lg-12 no-padder "><?php $this->load->view('RecentNotesView'); ?></div>
    <div class="col-lg-6 no-padder-l marginlr"><?php $this->load->view('LatestDealsView'); ?></div>
    <div class="col-lg-6 no-padder marginlr"><?php $this->load->view('TopDealsView'); ?></div>
  </div>

  <div class="col-lg-5 no-padder">
    <div class="col-lg-12 marginlr"><?php $this->load->view('LatestDiscussionsView'); ?></div>
  </div>
</div>
