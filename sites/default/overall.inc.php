<?php
class overall extends node{
public function overall($node, $pre){
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?=$node->meta['title'];?></title>
	<meta name="description" content="<?=$node->meta['description'];?>">
	<meta name="keywords" content="<?=$node->meta['keywords'];?>">
	<meta name="author" content="">
	
	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?=$node->paths['site'];?>images/favicon.ico">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/core/assets/skeleton/stylesheets/base.css">
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/core/assets/skeleton/stylesheets/skeleton-fluid.css">
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/<?=$node->paths['site'];?>/layout.css">
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/core/assets/jgrowl/jquery.jgrowl.min.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<? if(isset($pre->page_head)){ echo $pre->page_head; } // per page headers ?>

</head>
<body>



	<!-- Primary Page Layout
	================================================== -->

	<div class="container">	
		<? include 'header.inc.php'; ?><!-- header -->
		<? include 'nodes/'.$node->nid.'/page.inc.php'; ?>
		<? include 'footer.inc.php'; ?><!-- footer -->
	</div><!-- container -->

<!-- Javascript
	================================================== -->
	<script type="text/javascript" src="<?=$node->paths['base'];?>/core/assets/jquery/jquery-1.10.2.min.js"></script>
	<script type="text/javascript" src="<?=$node->paths['base'];?>/core/assets/jgrowl/jquery.jgrowl.min.js"></script>
<?
// status messages
if(isset($_SESSION['status_messages'])){
	$node->status_messages = array_merge($node->status_messages, $_SESSION['status_messages']);
	$_SESSION['status_messages'] = array();
}
if(!empty($node->status_messages)){
	echo '<script type="text/javascript">';
	foreach($node->status_messages as $key => $status){
		if(is_array($status)){
			foreach($status as $sub_status){
				if($key == 'admin'){
					echo "$.jGrowl('$sub_status', { life: 4500, sticky: true });";
				}else{
					echo "$.jGrowl('$sub_status', { life: 4500 });";
				}
			}
		}else{
			echo "$.jGrowl('$status', { life: 4500 });";
		}
	}
	echo '</script>';
}
?>
<!-- End Document
================================================== -->
</body>
</html>
<? } } ?>