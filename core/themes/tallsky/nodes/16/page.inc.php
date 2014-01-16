<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	
	<div class="six columns offset-by-two">
		<h3>Modify <?=$pre->r_name;?> Role</h3>
	<form action="<?=$this->current_address;?>" method="post">
		<div><strong>Role Name </strong> ex. writer</div>
		<p>
			<input name="name" type="text" style="width:280px;" value="<?=$pre->r_name;?>" />
		</p>
		
		<div><strong>Weight </strong> (for sorting roles in lists)</div>
		<p>
			<input name="weight" type="text" style="width:280px;" value="<?=$pre->r_weight;?>" />
		</p>
		
		<input name="modify_role" type="submit" value="Modify" class="button" />
	</form>
	</div>
</div>