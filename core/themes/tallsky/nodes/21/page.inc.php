<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/lib/codemirror.css">
<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/display/fullscreen.css">
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/lib/codemirror.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/htmlmixed/htmlmixed.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/xml/xml.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/javascript/javascript.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/css/css.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/clike/clike.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/php/php.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/display/fullscreen.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/selection/active-line.js"></script>


<form action="<?=$pre->node->current_address;?>" method="post" name="edit_page" id="target">
	<input type="hidden" name="update_file" value="1">
<div class="fourteen columns"><h3>Modify <?=$pre->filename;?></h3></div>
	<div class="two columns"><input name="edit_file" type="submit" value="Modify" class="button" ></div>
<div class="sixteen columns">
	<p style="border:#aebfcf solid 1px;"><textarea id="file" name="file"><?=htmlspecialchars($pre->file);?></textarea></p>
	<div class="offset-by-fourteen two columns"><input name="edit_file" type="submit" value="Modify" class="button" tabindex="2" ></div>
</div>
</form>
	
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("file"), {
mode: "<?=$pre->mode;?>",
indentUnit: 4,
indentWithTabs: true,
lineNumbers: true,
styleActiveLine: true,
enterMode: "keep",
tabindex: 1,
extraKeys: {
	"F11": function(cm) {
		cm.setOption("fullScreen", !cm.getOption("fullScreen"));
	},
	"Esc": function(cm) {
		if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
	},
	"Ctrl-S": function(cm) {
		$( "#target" ).submit();
	}
	}
});
editor.setSize('100%','100%');
</script>