<div class="six columns">
	<h3>New Site Profile</h3>
	<form action="<?=$this->current_address;?>" method="post">
		<div><strong>Profile Name </strong> ex. Awesome Theme</div>
		<p>
			<input name="name" type="text" style="width:280px;" value="<?=$pre->s_name;?>" />
		</p>
		
		<div><strong>path </strong> ex. awesome-theme</div>
		<p>
			<input name="path" type="text" style="width:280px;" value="<?=$pre->s_path;?>" />
		</p>
		
		<div><strong>Owner User ID </strong> ex. 10</div>
		<p>
			<input name="owner" type="text" style="width:80px;" value="<?=$pre->s_owner;?>" />
		</p>
		
		<input name="new_site" type="submit" value="Make" class="button" />
	</form>
</div>