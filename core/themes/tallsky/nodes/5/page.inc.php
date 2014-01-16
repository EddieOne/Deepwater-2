<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/page/new/" >New Page</a></li>
			<li><a href="<?=$node->paths['base'];?>/admin/reference/" >Core Reference</a></li>
		</ul>
	</div>
	<h3>View Pages</h3>
	
	<div class="sixteen columns" style="border-bottom:#86939e solid 1px;">
		<div class="one column"><strong>NID</strong></div>
		<div class="four columns"><strong>Title</strong></div>
		<div class="three columns"><strong>Alias</strong></div>
		<div class="two columns"><strong>Type</strong></div>
		<div class="two columns"><strong>Created</strong></div>
		<div class="two columns"><strong>Changed</strong></div>
		<div class="two columns"><strong>Options</strong></div>
	</div>
	<?=$pre->nodes_html();?>

</div>