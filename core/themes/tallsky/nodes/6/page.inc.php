<div class="sixteen columns">
	<div class="menu sixteen columns dashing" style="height:35px; line-height:35px; margin:-14px 0 14px 0;">
		<ul>
			<li><a href="<?=$node->paths['base'];?>/admin/site/new/" >New Site</a></li>
		</ul>
	</div>
	<h3>View Site Profiles</h3>
	
	<div class="two columns"><strong>Name</strong></div>
	<div class="two columns"><strong>Path</strong></div>
    <div class="two columns"><strong>Owner</strong></div>
	<div class="ten columns"><strong>Options</strong></div>
	<?=$pre->sites_html();?>

</div>