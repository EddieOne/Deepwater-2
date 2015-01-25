<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/lib/codemirror.css">
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/lib/codemirror.js"></script>
<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/selection/active-line.js"></script>

<div class="sixteen columns">
	<form action="<?=$pre->node->current_address;?>" method="post" id="target">
		<input type="hidden" name="update_crontab" value="1">
		<div class="fourteen columns"><h3>Crontabs</h3></div>
		<div class="two columns"><input name="edit_file" type="submit" value="Modify" class="button" ></div>
		<div class="sixteen columns">
			<p style="border:#aebfcf solid 1px;"><textarea id="crons" name="crons"><?=htmlspecialchars($pre->get_crons());?></textarea></p>
			<div class="offset-by-fourteen two columns"><input name="edit_file" type="submit" value="Modify" class="button" tabindex="2" ></div>
		</div>
	</form>

	<p>Currently, this feature will only work on environments that allow PHP to execute Linux commands and ones that have full shell access. With some hosting providers, you may be able to read crontabs but not write them. If either is the case, you will need to setup automated tasks through their hosting interface or contact their support.</p>
</div>	
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("crons"), {
indentUnit: 4,
indentWithTabs: true,
lineNumbers: true,
styleActiveLine: true,
enterMode: "keep",
tabindex: 1,
extraKeys: {
	"Ctrl-S": function(cm) {
		$( "#target" ).submit();
	}
	}
});
editor.setSize('100%','300px');
</script>