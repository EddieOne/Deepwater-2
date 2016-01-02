<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	
	<div class="sixteen columns">
	<h3>User Log</h3>
		<div class="two columns"><strong>IP Address</strong></div>
		<div class="one columns"><strong>Roles</strong></div>
		<div class="two columns"><strong>Identity</strong></div>
		<div class="five columns"><strong>Last Page</strong></div>
		<div class="two column"><strong>Views</strong></div>
		<div class="two columns"><strong>Appeared</strong></div>
		<div class="two columns"><strong>Last Visit</strong></div>
		<?=$pre->ulist;?>

	</div>
</div>