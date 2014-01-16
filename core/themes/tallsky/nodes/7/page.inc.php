<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	<h3>User Admin</h3>
	
	<div class="one column"><strong>ID</strong></div>
	<div class="two columns"><strong>Name</strong></div>
    <div class="three columns"><strong>Email</strong></div>
	<div class="two columns"><strong>Created</strong></div>
	<div class="two columns"><strong>Accessed</strong></div>
	<div class="two columns"><strong>Options</strong></div>
	<div class="two columns offset-by-four"></div>
	<?=$pre->users_html();?>

</div>