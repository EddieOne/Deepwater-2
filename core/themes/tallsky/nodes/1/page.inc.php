<div class="six columns">
	<h3>New Page</h3>
	<form action="<?=$this->current_address;?>" method="post">
		<div><strong>Title </strong></div>
		<p>
			<input name="title" type="text" style="width:350px;" value="<?=$pre->n_title;?>" spellcheck="true" />
		</p>
		
		<div><strong>Address Alias </strong> ex. /tools/subtraction/{num1}/{num2}/</div>
		<p>
			<input name="alias" type="text" style="width:350px;" value="<?=$pre->n_alias;?>" />
		</p>
		
		<div><strong>Node Type </strong> ex. html or php (used for categorization)</div>
		<p>
			<input name="type" type="text" style="width:240px;" value="<?=$pre->n_type;?>" />
		</p>
		
		<input name="new_page" type="submit" value="Make" class="button" />
	</form>
</div>
