<div class="sixteen columns">
	<h3>Update Deepwater</h3>
	<div class="one-third column">
		<p>Current version: <?=$pre->version;?> <br />
			Latest version: <?=$pre->remote_version;?></p>
	
		<form action="" method="post">
			<input name="update_deepwater" type="submit" value="Update Software" class="button" <? if($pre->version == $pre->remote_version){ echo ' disabled'; } ?> />
		</form>
	</div>
	
	<div class="one-third column">
		<strong>Backup Note</strong><br />
		<p>Before updating Deepwater it is wise to create a backup copy of the files and the database.</p>
	</div>
	
	<? if(!empty($pre->update_msg)){ ?>
	<div class="two-third column">
		<strong>Update Log</strong><br />
		<?=$pre->update_msg;?>
	</div>
	<? } ?>
</div>