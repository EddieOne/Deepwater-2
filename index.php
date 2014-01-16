<?php
session_start();
error_reporting(E_ALL);
$root_path = __DIR__;
include 'core/includes/node.inc.php';
$node = new node();
$pre = null;
if(file_exists($node->paths['pre'])){
	include $node->paths['pre'];
	$pre = new pre($node);
}
include $node->paths['site'].'/overall.inc.php';
$site = new overall($node, $pre);
?>