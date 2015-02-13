<?php 
  function bczCmp($a, $b) {
    return strcmp($a->sort_order, $b->sort_order);
  }
  function arrangeSettings($fields, $type) {
    $items = array();
    foreach ($fields as $field) { 
      if ($field->$type)  $items[] = $field;
    }
    usort($items, "bczCmp");
    return $items;
  }
?>

<div class="clearfix">
  <h3 class="disp-i-b">Settings</h3>
</div>

<!-- .accordion -->
<div class="panel-group m-b m-t-small" id="accordion2">

  
  
  
  
  
  
  

  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseOne', 'headingText' => 'User Management', 'actionButtons' => false)); ?>

    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <div class="panel-group" id="accordion3">
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion3', 'href' => '#collapse32', 'headingText' => 'Active Users', 'actionButtons' => true, 'buttons' => array(array('text' => 'add', 'modalId' => '#', 'class' => 'bcz-btn-add-user')))); ?>

            <div id="collapse32" class="panel-collapse in">
              <div class="panel-body text-small" id="users_container">
            <?php $this->load->view('UsersListingView'); ?>
              </div>
            </div>
          </div>
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion3', 'href' => '#collapse33', 'headingText' => 'Inactive Users', 'actionButtons' => false)); ?>

            <div id="collapse33" class="panel-collapse collapse">
              <div class="panel-body text-small" id="users_container1">
                  <?php $this->load->view('UsersListingInactiveView'); ?>
              </div>
            </div>
          </div>
          
          
        </div>
      </div>
    </div>
  </div>
  
  <div class="panel">
    <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapseTwo', 'headingText' => 'Organization Information', 'actionButtons' => false)); ?>

    <div id="collapseTwo" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php $this->load->view('OrganizationInfoView'); ?>
      </div>
    </div>
  </div>
  
<!--  
  <div class="panel">
    <?php //$this->load->view('PanelHeadingView', array('parent' => '#accordion3', 'href' => '#collapseFour', 'headingText' => 'Import and Export', 'actionButtons' => false)); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php //$this->load->view('ExportImportSettingsView'); ?>
      </div>
    </div>
  </div>
-->
</div>
<!-- / .accordion -->