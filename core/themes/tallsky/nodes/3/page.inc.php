<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/page/new/" >New Page</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/reference/" >Core Reference</a></li>
		</ul>
	</div>
</div>
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

<div class="sixteen columns">
	<form action="<?=$pre->node->current_address;?>" method="post" name="edit_page" id="target">
		<div class="sixteen columns dashing" style="margin:0 0 10px 0;">
			<div class="eight columns">
				<h3>Edit <?=$pre->edit_node->meta['title'];?></h3>
			</div>
			<div class="eight columns">
				<div class="eight columns">
					<h5 style="padding-top:6px;"><strong>Node id:</strong> <?=$pre->enid;?></h5>
				</div>
				<div class="eight columns">
					<input name="edit_page" type="submit" value="Modify" class="button" />
				</div>
			</div>
		</div>
	
		<div class="one-third column">
			<div><strong>Title </strong></div>
			<p><input name="title" type="text" style="width:350px;" value="<?=$pre->edit_node->meta['title'];?>" tabindex="1" /></p>
		</div>
		
		<div class="one-third column">
			<div><strong>Address Alias </strong> ex. /tools/subtraction/{num1}/{num2}/</div>
			<p><input name="alias" type="text" style="width:350px;" value="<?=$pre->edit_node->meta['alias'];?>" tabindex="2" /></p>
		</div>
		
		<div class="one-third column">
			<div><strong>Node Type </strong> ex. html or php (used for categorization)</div>
			<p><input name="type" type="text" style="width:240px;" value="<?=$pre->edit_node->meta['type'];?>" tabindex="3" /></p>
		</div>
		
		<div class="one-third column">
			<div><strong>Status </strong></div>
			<p>
				<select name="status" tabindex="4" >
					<option value="0">Private</option>
					<option value="1">Public</option>
				</select>
			</p>
		</div>
		
		<div class="one-third column">
			<div><strong>Cache Options </strong></div>
			<p>
				<select name="cache" tabindex="5" >
					<option>No Cache</option>
					<option>Private</option>
				</select>
			</p>
		</div>
		
		<div class="one-third column">
			<div class="eight columns">
				<div><strong>Version </strong> (Increments by 0.001)</div>
				<p><input name="version" type="text" style="width:180px;" value="<?=$pre->edit_node->meta['vid'];?>" tabindex="6" /></p>
			</div>
			<div class="eight columns">
				<div><strong>Skip Increment </strong> </div>
				<p><input name="skip" type="checkbox" value="true" tabindex="7" /></p>
			</div>
		</div>
		
		<div class="one-third column">
			<div><strong>Options </strong> (for customization)</div>
			<p><input name="options" maxlength="100" type="text" style="width:230px;" value="<?=$pre->edit_node->meta['options'];?>" tabindex="8" /></p>
		</div>
		
		<div class="one-third column">
			<div><strong>Meta Description </strong></div>
			<p><textarea name="description" tabindex="9" style="width:240px; max-width:240px; height:30px; resize:none;"><?=$pre->edit_node->meta['description'];?></textarea></p>
		</div>
		<div class="one-third column">
			<div><strong>Meta Keywords </strong></div>
			<p><textarea name="keywords" tabindex="10" style="width:240px; max-width:240px; height:30px; resize:none;"><?=$pre->edit_node->meta['keywords'];?></textarea></p>
		</div>
		
		<div class="one-third column">
			<div><strong>Site Profile</strong></div>
			<p>
				<select name="site" tabindex="11" >
					<?=$pre->site_html($pre->node, $pre->edit_node->meta['sid']);?>
				</select>
			</p>
		</div>
		
		<div class="one-third column">
			<div><strong>Permissions </strong></div>
			<?=$pre->role_html();?>
		</div>
		
		<div class="sixteen columns">
			<div><strong>Pre Page Code</strong> (Press F11 to view fullscreen) <span class="template button">Class Template</span></div>
			<p style="border:#aebfcf solid 1px;"><textarea id="pre_code" name="pre_code" ><?=$pre->en_pre;?></textarea></p>
		</div>
		
		<div class="sixteen columns">
			<div><strong>Page Code </strong>(Press F11 to view fullscreen)</div>
			<p style="border:#aebfcf solid 1px;"><textarea id="page_code" name="page_code" ><?=htmlspecialchars($pre->en_page);?></textarea></p>
		</div>
		<div class="offset-by-fourteen two columns"><input name="edit_page" type="submit" value="Modify" class="button" tabindex="6" /></div>
	</form>
</div>
<script>
var editor = CodeMirror.fromTextArea(document.getElementById("pre_code"), {
mode: "application/x-httpd-php",
indentUnit: 4,
indentWithTabs: true,
lineNumbers: true,
styleActiveLine: true,
enterMode: "keep",
tabindex: 12,
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
var editor2 = CodeMirror.fromTextArea(document.getElementById("page_code"), {
mode: "application/x-httpd-php",
indentUnit: 4,
indentWithTabs: true,
lineNumbers: true,
styleActiveLine: true,
enterMode: "keep",
tabindex: 13,
extraKeys: {
	"F11": function(cm) {
		cm.setOption("fullScreen", !cm.getOption("fullScreen"));
	},
	"Esc": function(cm) {
		if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
	},
	"Ctrl-S": function(cm) {
		$("#target").submit();
	}
	}
});
</script>