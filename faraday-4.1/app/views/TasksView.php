<div class="clearfix">
  <h3>Tasks list
  <input type="hidden" class="export" value="0" />
  	<?php if ($this->isAdmin && $tasks[0]) { ?><a href="<?=base_url()?>tasks/export" class="btn btn-sm btn-inverse pull-right m-l-small exporta" style="margin-top: -2px;"><i class="icon-arrow-up"></i> Export</a><?php } ?>
  	<a href="<?=base_url()?>tasks/add" class="btn btn-sm btn-inverse pull-right" style="margin-top: -2px;"><i class="icon-plus"></i> ADD</a>
  </h3>
</div>

<?php if ($tasks[0]) { ?>

  <?php $this->load->view('FiltersView', array('actionPath' => 'tasks', 'fSource' => $tasks)); ?>
  <div class="bcz-filters-content1">
  <label><input type="text" data-filter-action="<?=base_url()."tasks"?>" class="input-sm form-control tasks_search" placeholder="Search" value="" id="tasks_search"></label>
  </div>
  <div class="bcz-filters-content">
    <?php $this->load->view('DataTableViewOpp', array('sourcePath' => 'tasks/gettasksjson')); ?>
  </div>

<?php } else {

      $this->load->view('NoDataView', array('nodata' => 'No Tasks Available'));

} ?>