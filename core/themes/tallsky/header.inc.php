		<div class="sixteen columns alpha omega nav menu" style="margin:0 0 14px 0;">
			<div class="twelve columns">
				<ul>
					<li><a href="<?=$node->paths['base'];?>/admin/" >Home</a></li>
					<li><a href="<?=$node->paths['base'];?>/admin/site/" >Sites</a></li>
					<li><a href="<?=$node->paths['base'];?>/admin/plugin/" >Plugins</a></li>
					<li><a href="<?=$node->paths['base'];?>/admin/page/" >Pages</a></li>
					<li><a href="<?=$node->paths['base'];?>/admin/user/" >Users</a></li>
				</ul>
			</div>
			<div class="four columns">
				<ul style="float:right;">
					<li><a href="#" ><?=$node->user_name;?></a></li>
					<? if($node->is_admin($node->user_id)){ ?>
					<li><a href="<?=$node->current_address.'edit/';?>" >Edit Page</a></li>
					<? } ?>
				</ul>
			</div>
		</div>