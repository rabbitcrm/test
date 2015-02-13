<div class="clearfix">
  <h3>
  	Accounts list
    <input type="hidden" class="export" value="0" />
    <?php if ($this->isAdmin ) { ?>
     <a  href="<?=base_url()?>companies/import" class="btn btn-sm btn-inverse pull-right m-l-small import " style="margin-top: -2px;"><i class="icon-arrow-down"></i>Import</a>
     <?php } ?>
  	<?php if ($this->isAdmin && $companies[0]) { ?><a href="<?=base_url()?>companies/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>companies/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a>
  </h3>
</div>

<?php if ($companies[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'companies', 'fSource' => $companies)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."companies"?>" class="input-sm form-control accounts_search" placeholder="Search" value="" id="accounts_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('DataTableView', array('sourcePath' => 'companies/getcompaniesjson')); ?>
  </div>

<?php } else {

  $this->load->view('NoDataView', array('nodata' => 'No Accounts Available'));

} ?>