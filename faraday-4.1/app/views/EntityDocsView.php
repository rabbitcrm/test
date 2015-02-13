<div class="text-danger"></div>

<?php $this->load->view('UploadButtonView'); ?>

<?php if ($docs[0]) { ?>
  <?php foreach($docs as $did => $doc) {
    $this->load->view('SingleDocView', array('doc' => $doc, 'bottomLineFlag' => ($did < (count($docs) - 1))));
  } ?>
<?php } else { ?>
  <p class="bcz-no-data-msg h5 m-t-large">No documents uploaded yet.</p>
<?php } ?>