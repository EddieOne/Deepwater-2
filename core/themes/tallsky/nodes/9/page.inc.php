<div class="five columns offset-by-one">
	<h3>Modify <?=$pre->s_name;?> Site</h3>
	<form action="<?=$this->current_address;?>" method="post">
		<div><strong>Profile Name </strong> ex. Awesome Theme</div>
		<p>
			<input name="name" type="text" style="width:280px;" value="<?=$pre->s_name;?>" />
		</p>

		<div><strong>path </strong> ex. awesome-theme</div>
		<p>
			<input name="path" type="text" style="width:280px;" value="<?=$pre->s_path;?>" />
		</p>

		<div><strong>Owner User ID </strong> ex. 10</div>
		<p>
			<input name="owner" type="text" style="width:80px;" value="<?=$pre->s_owner;?>" />
		</p>

		<input name="modify_site" type="submit" value="Modify" class="button" />
	</form>
</div>

<div class="five columns">
	<h4>Files</h4>
<?
foreach ($pre->get_files() as $file){
	$path_parts = pathinfo($file);
	$ext = $path_parts['extension'];
	if($ext == 'php' || $ext == 'css'){
		echo '<a href="/admin/site/modify-file/'.$pre->s_path.'/'.$file.'/">'.$file.'</a><br>';
	}else{
		echo $file.'<br>';
	}
}
?>
</div>
