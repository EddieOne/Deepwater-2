<?php
class overall extends node{
public function overall($node){
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

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/core/assets/skeleton/stylesheets/base.css">
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/core/assets/skeleton/stylesheets/skeleton-fluid.css">
	<link rel="stylesheet" href="<?=$node->paths['base'];?>/<?=$node->paths['site'];?>/layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?=$node->paths['site'];?>images/favicon.ico">

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
	<script type="text/javascript" src="<?=self::$base_url.self::$install_path;?>/core/assets/jquery/jquery-1.10.2.min.js"></script>
<!-- End Document
================================================== -->
</body>
</html>
<noscript><link rel="stylesheet" href="<?=$node->paths['style'];?>"></noscript>


<? } } ?>