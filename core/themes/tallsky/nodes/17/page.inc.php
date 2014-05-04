<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	<h3>Modify User</h3>
</div>
	
<div class="three columns offset-by-two">
	<form action="" method="post">
		<label for="tuser_id">User ID</label>
		<input name="tuser_id" type="text" value="<?=$pre->user->user_id;?>" disabled />
		
		<label for="user_name">Name</label>
		<input name="user_name" type="text" value="<?=$pre->user->user_name;?>" />

		<label for="user_pass">Passphrase (unchanged when empty) </label>
		<input name="user_pass" type="password" value="<?=$pre->user->user_pass;?>" />

		<label for="mail">Email Address </label>
		<input name="mail" type="email" value="<?=$pre->user->mail;?>" />

		<label for="status">Status (int) </label>
		<input name="status" type="number" value="<?=$pre->user->status;?>" />

		<label for="profile">Social Profile </label>
		<input name="profile" type="url" value="<?=$pre->user->profile;?>" />

		<label for="notify">Notify (int) </label>
		<input name="notify" type="number" value="<?=$pre->user->notify;?>" />
		
		<label for="misc">Misc. (not used by core) </label>
		<input name="misc" type="text" value="<?=$pre->user->misc;?>" />

		<input class="button full-width" name="modify_user" value="Modify User" type="submit" />

	</form>
</div>
<div class="ten columns offset-by-one">
	<h4>User's Roles</h4>
	<form action="" method="post">
	<?
	$defined_roles = $pre->node->get_defined_roles();
	$roles = $pre->node->get_user_roles($pre->uid);
	foreach($defined_roles as $name => $rid){
		echo $name;
		echo '<select name="permissions['.$rid.']">';
		if(in_array($rid, $roles)){
			echo '<option value="true">Present</option>';
			echo '<option value="fales">Remove</option>';
		}else{
			echo '<option value="false">Not Present</option>';
			echo '<option value="true">Add</option>';
		}
		echo '</select>';
	}
	?>
	<input class="button" name="modify_roles" value="Update Roles" type="submit" />
	</form>
</div>