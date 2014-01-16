<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	<h3>Create User</h3>
</div>
	
<div class="three columns offset-by-two">
	<form action="" method="post">
		<label for="user_name">Name</label>
		<input name="user_name" type="text" />

		<label for="user_pass">Paraphrase </label>
		<input name="user_pass" type="password" />

		<label for="mail">Email Address </label>
		<input name="mail" type="email" />

		<label for="status">Status (int) </label>
		<input name="status" type="number" />

		<label for="profile">Social Profile </label>
		<input name="profile" type="url" />

		<label for="notify">Notify (int) </label>
		<input name="notify" type="number" />

		<input class="button full-width" name="create_user" value="Create User" type="submit" />

	</form>
</div>