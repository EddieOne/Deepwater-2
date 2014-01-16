<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	
	<div class="six columns offset-by-two">
	<h3>Delete <?=$pre->r_name;?> Role?</h3>
	<p>Deleting a role that is used by pages may cause them to become inaccessible even if you have permission.</p>
	<form action="<?=$this->current_address;?>" method="post">		
		<p><input name="delete_role" type="submit" value="Confirm Deletion" class="button" /></p>
	</form>
	</div>
</div>