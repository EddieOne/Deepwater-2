<div class="sixteen columns">
	<h3><?=$pre->node->meta['title'];?></h3>
		
	<? if($pre->action == 'uninstall'){ ?>
		Discarding a plugin's data is irreversible and should be done with care. Would you like to keep the plugin database records?
		<form action="<?=$pre->node->current_address;?>" method="post">
			<input name="keep_records" type="submit" value="Keep Records" class="button" style="margin:5px;" />
			<input name="discard_records" type="submit" value="Discard Records" class="button" style="margin:5px;" />
		</form>
	<? } ?>
</div>