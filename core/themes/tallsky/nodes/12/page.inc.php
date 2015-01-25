<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/page/new/" >New Page</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/reference/" >Core Reference</a></li>
		</ul>
	</div>
	<h3>Core Reference</h3>
	<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/lib/codemirror.css">
	<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/display/fullscreen.css">
	<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/dialog/dialog.css">
	<link rel="stylesheet" href="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/search/matchesonscrollbar.css">
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/lib/codemirror.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/htmlmixed/htmlmixed.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/xml/xml.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/javascript/javascript.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/css/css.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/clike/clike.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/mode/php/php.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/display/fullscreen.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/selection/active-line.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/dialog/dialog.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/search/searchcursor.js"></script>
	<script src="<?=$pre->node->paths['base'];?>/core/assets/codemirror/addon/search/search.js"></script>

	<div class="eight columns add-bottom"><h6>pdo.inc.php</h6>
		<textarea id="pdo" name="pdo" disabled><?=$pre->core['pdo'];?></textarea>
	</div>
	<div class="eight columns add-bottom"><h6>navigator.inc.php</h6>
		<textarea id="navigator" name="navigator" disabled><?=$pre->core['navigator'];?></textarea>
	</div>
	<div class="eight columns add-bottom"><h6>authentication.inc.php</h6>
		<textarea id="auth" name="auth" disabled><?=$pre->core['auth'];?></textarea>
	</div>
	<div class="eight columns add-bottom"><h6>node.inc.php</h6>
		<textarea id="node" name="node" disabled><?=$pre->core['node'];?></textarea>
	</div>
	<div class="eight columns add-bottom"><h6>validation.inc.php</h6>
		<textarea id="validation" name="validation" disabled><?=$pre->core['validation'];?></textarea>
	</div>
	<div class="eight columns"><h6></h6>
		
	</div>

</div>
<script>
	function viewer(id){
    CodeMirror.fromTextArea(document.getElementById(id), {
       mode: "application/x-httpd-php",
		indentUnit: 4,
		indentWithTabs: true,
		lineNumbers: true,
		styleActiveLine: true,
		readOnly: true,
		enterMode: "keep",
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
}
viewer('pdo');
viewer('navigator');
viewer('auth');
viewer('node');
viewer('validation');
</script>