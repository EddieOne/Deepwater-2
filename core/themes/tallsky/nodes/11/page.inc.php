<div class="six columns offset-by-two">
	<h3>Delete <?=$pre->d_node->meta['title'];?> Page?</h3>
	<p>Deleting a page cannot be undone. All code files, revision backups, and meta information will be lost.</p>
	<form action="<?=$this->current_address;?>" method="post">		
		<p><input name="delete_node" type="submit" value="Confirm Deletion" class="button" /></p>
	</form>
</div>