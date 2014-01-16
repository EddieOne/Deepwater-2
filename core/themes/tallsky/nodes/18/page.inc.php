<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	
	<div class="six columns offset-by-two">
	<h3>Delete <?=$pre->u_name;?>'s Account?</h3>
	<p>Deleting a user will remove the accounts information from the database.</p>
	<form action="<?=$this->current_address;?>" method="post">		
		<p><input name="delete_user" type="submit" value="Confirm Deletion" class="button" /></p>
	</form>
	</div>
</div>