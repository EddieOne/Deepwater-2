<div class="sixteen columns">
<h3>Admin Control Panel</h3>
	
	<div class="eight columns">
		<div style="width:200px; float:left;">
			<h5>Memory Usage</h5>
			<p><?=$pre->mem;?> / <?=$pre->mem_limit;?> mb</p>
		</div>
		<div style="width:200px; float:left;">
			<h5>Peak Memory Usage</h5>
			<p><?=$pre->mem_peak;?> / <?=$pre->mem_limit;?> mb</p>
		</div>
		<div style="width:200px; float:left;">
			<h5>Average Load</h5>
			<p><?=$pre->loadavg;?>%</p>
		</div>
		<div style="width:200px; float:left;">
			<h5>IO Wait</h5>
			<p><?=$pre->iowait;?>%</p>
		</div>
	</div>
	<div class="eight columns">
	</div>
</div>