<div class="six columns offset-by-two">
	<h3>Delete <?=$pre->s_name;?> Site?</h3>
	<p>Deleting a site profile will also delete all page files stored within the site directory. Page meta data will still be listed in the nodes database but will not function without the page files.</p>
	<form action="<?=$this->current_address;?>" method="post">		
		<p><input name="delete_site" type="submit" value="Confirm Deletion" class="button" /></p>
	</form>
</div>