<article class="media m-b-large">
	<div class="input-group">
		<input type="text" name="note" placeholder="Input your note here" class="form-control">
		<span class="input-group-btn">
			<button class="btn btn-inverse" type="button" data-loading-text="Adding..." id="add_note" data-id="<?=$entityId?>" data-type="<?=$entityType?>" data-action="<?=base_url()?>notes/create">ADD</button>
		</span>
	</div>
</article>

<?php if ($notes[0]) { ?>
  <?php foreach($notes as $nid => $note) {
    $this->load->view('SingleNoteView', array('note' => $note, 'bottomLineFlag' => ($nid < (count($notes) - 1))));
  } ?>
<?php } else { ?>
  <p class="bcz-no-data-msg h5">No notes added yet.</p>
<?php } ?>