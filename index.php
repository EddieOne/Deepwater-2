<?php
session_start();
error_reporting(E_ALL);
$root_path = dirname(__FILE__);
if(file_exists('core/includes/configuration.inc.php')){
	include 'core/includes/node.inc.php';
}else{
	header('Location: install.php');
	exit();
}
$node = new node();
$pre = null;
if(file_exists($node->paths['pre'])){
	include $node->paths['pre'];
	$pre = new pre($node);
}
include $node->paths['site'].'/overall.inc.php';
$site = new overall($node, $pre);
?>