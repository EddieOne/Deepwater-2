<div class="sixteen columns">
	<h3>Update Deepwater</h3>
	<div class="one-third column">
		<p>Current version: <?=$pre->version;?> <br />
			Latest version: <?=$pre->remote_version;?></p>
	
		<form action="" method="post">
			<input name="update_deepwater" type="submit" value="Update Software" class="button" <? if($pre->version == $pre->remote_version){ echo ' disabled'; } ?> />
		</form>
	</div>
</div>