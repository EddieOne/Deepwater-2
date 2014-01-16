<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/user/new/" >New User</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/roles/" >User Roles</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/user/log/sort-by/default/" >User Log</a></li>
		</ul>
	</div>
	
	<div class="eight columns alpha omega">
		<h3>Defined Roles</h3>
		<div class="six columns"><strong>Name</strong></div>
   		<div class="four columns"><strong>Weight</strong></div>
		<div class="six columns"><strong>Options</strong></div>
		<?=$pre->roles_html();?>
		<div class="three columns offset-by-three"></div>
	</div>
	<div class="eight columns alpha omega">
		<h3 class="add-bottom">New Role</h3>
		<form method="post" action="<?=$pre->node->current_address;?>">
			<div class="six columns">
				<label for="n_role_name">Name </label>
				<input type="text" name="n_role_name" />
			</div>
			<div class="three columns">
				<label for="n_role_weight">Weight </label>
				<input type="number" name="n_role_weight" style="width:50px;" />
			</div>
			<div class="four columns">
				<label for="new_role">   </label>	
				<input type="submit" name="new_role" value="Create Role" class="button full-width" />
			</div>
		</form>
		<div class="offset-by-two"> </div>
	</div>
</div>