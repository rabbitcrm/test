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
  <h3 class="disp-i-b">Advanced Settings</h3>
</div>

<!-- .accordion -->

  
  

  
  


<div class="panel-group m-b m-t-small" id="accordion2">

<div class="panel">
   <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse3222', 'headingText' => 'Campaign Settings', 'actionButtons' => false)); ?>
   
    <div id="collapse3222" class="panel-collapse collapse">
    
              <div class="panel-body text-small">
                <?php $this->load->view('CampaignSettingsView'); ?>
              </div>
            </div>
          </div>
          
          
 <div class="panel">
   <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse32', 'headingText' => 'Lead & Opportunity Settings', 'actionButtons' => false)); ?>
   
    <div id="collapse32" class="panel-collapse collapse">
    
              <div class="panel-body text-small">
                <?php $this->load->view('LeadDealSettingsView'); ?>
              </div>
            </div>
          </div>
  
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse33', 'headingText' => 'Account, Contact & Task Settings', 'actionButtons' => false)); ?>

            <div id="collapse33" class="panel-collapse collapse">
              <div class="panel-body text-small">
                <?php $this->load->view('ComConTaskSettingsView'); ?>
              </div>
            </div>
          </div>
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse35', 'headingText' => 'Quote & Ticket Settings', 'actionButtons' => false)); ?>

            <div id="collapse35" class="panel-collapse collapse">
              <div class="panel-body text-small">
                <?php $this->load->view('QuoteCaseSettingsView'); ?>
              </div>
            </div>
          </div>
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse31', 'headingText' => 'Product & Tax Settings', 'actionButtons' => false)); ?>
            
            <div id="collapse31" class="panel-collapse collapse">
              <div class="panel-body text-small">
                <?php $this->load->view('ProductTaxSettingsView'); ?>
              </div>
            </div>
          </div>
          
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse37', 'headingText' => 'Customize Record Numbering', 'actionButtons' => false)); ?>
            
            <div id="collapse37" class="panel-collapse collapse">
              <div class="panel-body text-small">
                <?php $this->load->view('coustomizeRecordNumbering'); ?>
              </div>
            </div>
          </div>
          
          <div class="panel">
            <?php $this->load->view('PanelHeadingView', array('parent' => '#accordion2', 'href' => '#collapse38', 'headingText' => ' Terms and Conditions', 'actionButtons' => false)); ?>
            
            <div id="collapse38" class="panel-collapse collapse">
              <div class="panel-body text-small">
                <?php $this->load->view('TermsAndConditionsView'); ?>
              </div>
            </div>
          </div>
          
        </div>
      


<!--  
  <div class="btn-toolbar" data-role="editor-toolbar" data-target="#editor">
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="icon-font"></i><b class="caret"></b></a>
          <ul class="dropdown-menu">
          </ul>
        </div>
      <div class="btn-group">
        <a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="icon-text-height"></i>&nbsp;<b class="caret"></b></a>
          <ul class="dropdown-menu">
          <li><a data-edit="fontSize 5"><font size="5">Huge</font></a></li>
          <li><a data-edit="fontSize 3"><font size="3">Normal</font></a></li>
          <li><a data-edit="fontSize 1"><font size="1">Small</font></a></li>
          </ul>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="icon-bold"></i></a>
        <a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="icon-italic"></i></a>
        <a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="icon-strikethrough"></i></a>
        <a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="icon-underline"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="icon-list-ul"></i></a>
        <a class="btn" data-edit="insertorderedlist" title="Number list"><i class="icon-list-ol"></i></a>
        <a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="icon-indent-left"></i></a>
        <a class="btn" data-edit="indent" title="Indent (Tab)"><i class="icon-indent-right"></i></a>
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="icon-align-left"></i></a>
        <a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="icon-align-center"></i></a>
        <a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="icon-align-right"></i></a>
        <a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="icon-align-justify"></i></a>
      </div>
      <div class="btn-group">
		  <a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="icon-link"></i></a>
		    <div class="dropdown-menu input-append">
			    <input class="span2" placeholder="URL" type="text" data-edit="createLink"/>
			    <button class="btn" type="button">Add</button>
        </div>
        <a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="icon-cut"></i></a>

      </div>
      
      <div class="btn-group">
        <a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="icon-picture"></i></a>
        <input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
      </div>
      <div class="btn-group">
        <a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="icon-undo"></i></a>
        <a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="icon-repeat"></i></a>
      </div>
      <input type="text" data-edit="inserttext" id="voiceBtn" x-webkit-speech="">
    </div>

    <div id="editor">
      Go ahead&hellip;
    </div>
    <style>
 

#editor {
	max-height: 250px;
	height: 250px;
	background-color: white;
	border-collapse: separate; 
	border: 1px solid rgb(204, 204, 204); 
	padding: 4px; 
	box-sizing: content-box; 
	-webkit-box-shadow: rgba(0, 0, 0, 0.0745098) 0px 1px 1px 0px inset; 
	box-shadow: rgba(0, 0, 0, 0.0745098) 0px 1px 1px 0px inset;
	border-top-right-radius: 3px; border-bottom-right-radius: 3px;
	border-bottom-left-radius: 3px; border-top-left-radius: 3px;
	overflow: scroll;
	outline: none;
}
#voiceBtn {
  width: 20px;
  color: transparent;
  background-color: transparent;
  transform: scale(2.0, 2.0);
  -webkit-transform: scale(2.0, 2.0);
  -moz-transform: scale(2.0, 2.0);
  border: transparent;
  cursor: pointer;
  box-shadow: none;
  -webkit-box-shadow: none;
}

div[data-role="editor-toolbar"] {
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

.dropdown-menu a {
  cursor: pointer;
}

 </style>
 
  <div class="panel">
    <?php //$this->load->view('PanelHeadingView', array('parent' => '#accordion3', 'href' => '#collapseFour', 'headingText' => 'Import and Export', 'actionButtons' => false)); ?>

    <div id="collapseFour" class="panel-collapse collapse">
      <div class="panel-body text-small">
        <?php //$this->load->view('ExportImportSettingsView'); ?>
      </div>
    </div>
  </div>
-->

<!-- / .accordion -->