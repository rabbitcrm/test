<div class="clearfix">
  <h3 class="disp-i-b">Documents</h3>

  <?php if ($this->isAdmin) { ?>
    <div class="btn-group pull-right m-t">
      <button data-toggle="dropdown" class="btn btn-sm btn-white dropdown-toggle">
        <span class="dropdown-label" data-placeholder="Action">Action</span>
        <span class="caret"></span>
      </button>

      <ul class="dropdown-menu doc-actions">
        <li><a href="#create_folder_modal"><i class="icon-plus"></i>Create Folder</a></li>
        <li><a href="#upload_doc_modal"><i class="icon-upload-alt"></i>Upload</a></li>
      </ul>
    </div>
  <?php } ?>
</div>
<?php if ($nodes) { //echo "<pre>"; print_r($nodes); ?>
  <div class="col-sm-12 no-padder">
    <section class="panel">
    	<div class="panel-body no-padder docs-list">
        <div class="list-header">
          <div class="col-xs-10 m-b-mini m-t-small h4">Name</div>
          <div class="col-xs-2 m-b-mini m-t-small h4">Size</div>
        </div>
        <?php foreach ($nodes as $nid => $node) { ?>
          <?php $this->load->view('SingleDocInfoView', array('docData' => $node, 'docKey' => $nid, 'docCount' => $docCount, 'path' => $this->user->org_id.'/'.'')); ?>
        <?php } ?>
    	</div>
    </section>
  </div>
<?php } else { $this->load->view('NoDataView'); } ?>