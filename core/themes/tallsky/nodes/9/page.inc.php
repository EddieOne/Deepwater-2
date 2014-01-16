<div class="six columns offset-by-two">
	<h3>Modify <?=$pre->s_name;?> Site</h3>
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
		
		<input name="modify_site" type="submit" value="Modify" class="button" />
	</form>
</div>