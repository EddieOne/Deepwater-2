<?
// protect the config file
$config_file = 'core/includes/configuration.inc.php';
		
if(!empty($_POST['step_3'])){
	$step_num = 3;
}else if(!empty($_POST['step_2'])){
	$step_num = 2;
}else{
	$step_num = 1;
}

// step 1 code
if($step_num == 1){
	$env_vars = check_environment();	
	$env_html = '';
	$disable_but = '';
	foreach($env_vars as $var){
		$pass = $var['status'];
		$name = $var['name'];
		if(!$pass){
			$disable_but = ' disabled';
		}
		$env_html .= environment_html($pass, $name);
	}
}

//step 2 code
if($step_num == 2){	
	// text input defaults
	$host = 'localhost';
	$port = 3306;
	$lowAlpha = "abcdefghijklmnopqrstuvwxyz";
	$highAlpha = "ABCDEFGHIJKLMNPQRSTUVWXYZ";
	$numeric = "0123456789";
	$salt = substr(str_shuffle($lowAlpha.$highAlpha.$numeric), 0, 20);
	$base = substr(str_shuffle('abcdef'.$numeric), 0, 16);
	$domain = 'http://'.$_SERVER['HTTP_HOST'];
	$path = str_replace('/install.php', '', $_SERVER['REQUEST_URI']);
	
	// step 2 submit
	if(!empty($_POST['user'])){
		
		// @TODO replace this with a text file template or yaml config
		// to avoid pre install errors on hosts with < PHP 5.3.0
		$config = <<<'EOT'
<?php
class configuration {
	// database connection 
	public $db_host='%%host%%';
	public $db_user='%%user%%';
	public $db_pass='%%pass%%';
	public $db_name='%%name%%';
	public $db_port=%%port%%;
	public $hash_salt = '%%salt%%';
	public $number_base = '%%base%%';
	
	// common varibles
	public static $base_url = '%%domain%%';
	public static $install_path = '%%path%%';
	public static $default_site = 'default';
	public static $admin_theme = 'tallsky';
	public static $site_status = 'online';
	public static $offline_message = 'We are undergoing maintenance. Please check back soon. ';
	public static $ban_message = 'You have been banned.';
}
%%?%%>
EOT;

		$search = array('%%host%%', '%%user%%', '%%pass%%', '%%name%%', '%%port%%', '%%salt%%', '%%base%%', '%%domain%%', '%%path%%', '%%?%%');
		$replace = array($_POST['host'], $_POST['user'], $_POST['pass'], $_POST['name'], $_POST['port'], $_POST['salt'], $_POST['base'], $_POST['domain'], $_POST['path'], '?');
		$config_text = str_replace($search, $replace, $config);
		// config protect
		if(file_exists($config_file)){ echo 'Installation error '; exit(); }
		$config_result = file_put_contents($config_file, $config_text);
		if($config_result !== false){
			$step_num = 3;
			$dw_sql = array();
$dw_sql[] = "CREATE TABLE IF NOT EXISTS `banned` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary id.',
  `ip` varchar(45) NOT NULL COMMENT 'Ip address, could be ipv6.',
  `reason` int(3) unsigned NOT NULL COMMENT 'Reason code for customization.',
  `time` int(11) unsigned NOT NULL COMMENT 'Unix time of ban event.',
  `expire` int(11) unsigned NOT NULL COMMENT 'When to remove the ban.',
  PRIMARY KEY (`pid`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `defined_roles` (
  `rid` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `weight` int(3) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `name` (`name`,`weight`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;";

$dw_sql[] = "INSERT INTO `defined_roles` (`rid`, `name`, `weight`) VALUES
(1, 'admin', 1),
(2, 'registered', 2),
(3, 'guest', 3);";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `flood_watch` (
  `fid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `expire` int(11) unsigned NOT NULL,
  `event` varchar(30) NOT NULL,
  PRIMARY KEY (`fid`),
  KEY `time` (`time`,`expire`,`event`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `nodes` (
  `nid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Node id.',
  `sid` int(11) unsigned NOT NULL COMMENT 'The site id that the node belongs to.',
  `vid` float(6,3) NOT NULL COMMENT 'Countes revisions and is used for creating backups. ',
  `user_id` int(11) unsigned NOT NULL,
  `status` int(3) NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `changed` int(11) unsigned NOT NULL,
  `type` varchar(50) NOT NULL COMMENT 'For custom categories. Not used by the core.',
  `alias_route` varchar(20) NOT NULL COMMENT 'The first sub alias. ex. site/route/extra/stuff',
  `alias` varchar(100) NOT NULL COMMENT 'The full alias to math against incoming addresses based on inital route.',
  `options` varchar(100) NOT NULL COMMENT 'For customization. Not used by the core.',
  `title` varchar(120) NOT NULL COMMENT 'The title of the node.',
  `description` varchar(255) NOT NULL COMMENT 'page meta description for seo.',
  `keywords` varchar(255) NOT NULL COMMENT 'page meta keywords for seo.',
  PRIMARY KEY (`nid`),
  KEY `type` (`type`,`user_id`,`created`,`alias_route`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Nodes are useally pages. Sometimes nodes are used as parts of other nodes.' AUTO_INCREMENT=1003 ;";

$dw_sql[] = "INSERT INTO `nodes` (`nid`, `sid`, `vid`, `user_id`, `status`, `created`, `changed`, `type`, `alias_route`, `alias`, `options`, `title`, `description`, `keywords`) VALUES
(1, 1, 1.007, 1, 0, 1389085059, 1389085059, 'core', 'admin', '/admin/page/new/', '', 'New Page', '', ''),
(2, 1, 1.009, 1, 0, 1389104903, 1389104903, 'core', 'admin', '/admin/user/login/', '', 'Admin Login', '', ''),
(3, 1, 1.001, 1, 0, 1388645558, 1388645558, 'core', 'admin', '/admin/page/editor/', '', 'Edit Page', '', ''),
(4, 1, 1.004, 1, 0, 1389006369, 1389006369, 'core', 'admin', '/admin/', '', 'Admin Control Panel', '', ''),
(5, 1, 0.051, 1, 0, 1389136446, 1389136446, 'core', 'admin', '/admin/page/', '', 'View Pages', '', ''),
(6, 1, 0.022, 1, 0, 1389071628, 1389071628, 'core', 'admin', '/admin/site/', '', 'View Sites', '', ''),
(7, 1, 0.012, 1, 0, 1389219832, 1389219832, 'core', 'admin', '/admin/user/', '', 'View Users', '', ''),
(8, 1, 0.003, 1, 0, 1389064841, 1389064841, 'core', 'admin', '/admin/site/new/', '', 'New Site', '', ''),
(9, 1, 0.012, 1, 0, 1389133579, 1389133579, 'core', 'admin', '/admin/site/modify/{sid}/', '', 'Modify Site', '', ''),
(10, 1, 0.007, 1, 0, 1389082508, 1389082508, 'core', 'admin', '/admin/site/delete/{sid}/', '', 'Delete Site', '', ''),
(11, 1, 0.016, 1, 0, 1389088023, 1389088023, 'core', 'admin', '/admin/page/delete/{nid}/', '', 'Delete Page', '', ''),
(12, 1, 0.005, 1, 0, 1389091203, 1389091203, 'core', 'admin', '/admin/reference/', '', 'Core Reference', '', ''),
(13, 1, 0.015, 1, 0, 1389250832, 1389250832, 'core', 'admin', '/admin/user/new/', '', 'New User', '', ''),
(14, 1, 0.091, 1, 0, 1389220120, 1389220120, 'core', 'admin', '/admin/user/roles/', '', 'User Roles', '', ''),
(15, 1, 0.006, 1, 0, 1389220189, 1389220189, 'core', 'admin', '/admin/user/roles/delete/{rid}/', '', 'Delete Role', '', ''),
(16, 1, 0.008, 1, 0, 1389220157, 1389220157, 'core', 'admin', '/admin/user/roles/modify/{rid}/', '', 'Modify Role', '', ''),
(17, 1, 0.027, 1, 0, 1389600496, 1389600496, 'core', 'admin', '/admin/user/modify/{uid}/', '', 'Modify User', '', ''),
(18, 1, 0.006, 1, 0, 1389250668, 1389250668, 'core', 'admin', '/admin/user/delete/{uid}/', '', 'Delete User', '', ''),
(19, 1, 0.008, 1, 0, 1389245479, 1389245479, 'core', 'admin', '/admin/user/log/sort-by/{sort}/', '', 'User Log', '', ''),
(20, 1, 0.030, 1, 0, 1391765430, 1391765430, 'core', 'admin', '/admin/update/', '', 'Update Deepwater', '', ''),
(1000, 1, 1.004, 1, 0, 1389137430, 1389137430, 'html', 'frontpage', '/frontpage/', '', 'Welcome to Deepwater', '', ''),
(1001, 1, 1.100, 1, 1, 1388039909, 1388039909, 'default', '404', '/404/', '', 'Page Not Found', '', ''),
(1002, 1, 0.002, 1, 0, 1389048810, 1389048810, 'default', 'access-denied', '/access-denied/', '', 'Access Denied ', '', '');";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `nodes_roles` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'primary id.',
  `nid` int(11) unsigned NOT NULL COMMENT 'The node the permission belongs to.',
  `rid` int(11) unsigned NOT NULL COMMENT 'The role or group the permission belongs to.',
  `auth` varchar(2) NOT NULL COMMENT 'Permission. r =  read, w = write, rw = both',
  PRIMARY KEY (`pid`),
  KEY `nid` (`nid`,`rid`,`auth`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;";

$dw_sql[] = "INSERT INTO `nodes_roles` (`pid`, `nid`, `rid`, `auth`) VALUES
(1, 1, 1, 'rw'),
(2, 2, 1, 'rw'),
(3, 3, 1, 'rw'),
(4, 2, 3, 'r'),
(5, 4, 1, 'rw'),
(6, 5, 1, 'rw'),
(7, 6, 1, 'rw'),
(8, 7, 1, 'rw'),
(9, 8, 1, 'rw'),
(10, 9, 1, 'rw'),
(11, 10, 1, 'rw'),
(12, 11, 1, 'rw'),
(13, 12, 1, 'rw'),
(14, 13, 1, 'rw'),
(15, 14, 1, 'rw'),
(16, 15, 1, 'rw'),
(17, 16, 1, 'rw'),
(18, 17, 1, 'rw'),
(19, 18, 1, 'rw'),
(20, 19, 1, 'rw'),
(21, 20, 1, 'rw')
(22, 1000, 1, 'rw'),
(23, 1000, 3, 'r'),
(24, 1001, 3, 'r'),
(25, 1001, 1, 'rw'),
(26, 1002, 1, 'rw');";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `sites` (
  `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `site_name` varchar(60) NOT NULL,
  `site_path` varchar(50) NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;";

$dw_sql[] = "INSERT INTO `sites` (`sid`, `user_id`, `site_name`, `site_path`) VALUES
(1, 1, 'Default', 'default');";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'The user''s id.',
  `user_name` varchar(30) NOT NULL,
  `name_slug` varchar(30) NOT NULL,
  `user_pass` varchar(48) NOT NULL COMMENT 'hashed using tiger192,3',
  `mail` varchar(130) NOT NULL,
  `token` varchar(100) NOT NULL COMMENT 'Login token.',
  `created` int(11) unsigned NOT NULL COMMENT 'Unix time of account creation.',
  `accessed` int(11) unsigned NOT NULL COMMENT 'Unix time of last login.',
  `status` int(3) NOT NULL COMMENT '0 = banned, 1 = email not confirmed, 2 = active',
  `profile` varchar(140) NOT NULL COMMENT 'Facebook, twitter, or google+ profile link.',
  `notify` int(3) NOT NULL COMMENT 'Email notify.',
  `misc` varchar(20) NOT NULL COMMENT 'extra space for customization.',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `name_slug` (`name_slug`),
  KEY `mail` (`mail`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `users_roles` (
  `pid` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Primary id.',
  `user_id` int(11) unsigned NOT NULL COMMENT 'User if that the roles belongs to.',
  `rid` int(11) unsigned NOT NULL COMMENT 'The role id that the user has or belongs to.',
  PRIMARY KEY (`pid`),
  KEY `user_id` (`user_id`,`rid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

$dw_sql[] = "CREATE TABLE IF NOT EXISTS `user_log` (
  `lid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(45) NOT NULL,
  `role` varchar(30) NOT NULL,
  `identity` varchar(50) NOT NULL,
  `last_page` varchar(100) NOT NULL,
  `views` int(11) unsigned NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned NOT NULL,
  PRIMARY KEY (`lid`),
  KEY `update` (`ip`,`identity`),
  KEY `sorting` (`views`,`start`,`end`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		
			include_once 'core/includes/pdo.inc.php';
			$db = new pdo_db();
			foreach($dw_sql as $sql){
				$db->execute($sql);
			}
		}
	}
}

//step 3 code
if($step_num == 3){
	include_once 'core/includes/pdo.inc.php';
	include_once 'core/includes/validation.inc.php';
	$db = new pdo_db();
	if(!empty($_POST['name']) && !empty($_POST['mail']) && !empty($_POST['pass'])){
		$name = $_POST['name'];
		$slug = validation::make_friendly($name);
		$hashpass = hash('tiger192,3', $db->hash_salt.$_POST['pass']);
		$time = time();
		
		$db->execute( "INSERT INTO users SET user_name = ?, name_slug = ?, user_pass = ?, mail = ?, created = ?, status = 2, notify = 1",array(
														$name, $slug, $hashpass, $_POST['mail'], $time));
		$admin_id = $db->last_insert();
		
		$db->execute( "INSERT INTO users_roles SET user_id = ?, rid = 1",array($admin_id));
		
		chmod("install.php", 0200);
		header('Location: admin/'); 
	}
}




function check_environment(){
	$environment = array();
	
	$environment['os']['name'] = 'Linux operating system';
	if (PHP_OS == 'Linux') {
		$environment['os']['status'] = true;
	}else{
		$environment['os']['status']= false;
	}
	
	$environment['php']['name'] = 'PHP version > 5.3.3';
	if (version_compare(phpversion(), '5.3.3', '>=')) {
		$environment['php']['status'] = true;
	}else{
		$environment['php']['status'] = false;
	}
	
	if(function_exists('apache_get_modules')){
		$environment['rewrite']['name'] = 'Apache module mod_rewrite found';
		if (in_array('mod_rewrite', apache_get_modules())) {
			$environment['rewrite']['status'] = true;
		}else{
			$environment['rewrite']['status'] = false;
		}
	}
	
	$exts = get_loaded_extensions();
		
	$environment['PDO']['name'] = 'PHP PDO extention found';
	if (in_array('PDO', $exts)) {
		$environment['PDO']['status'] = true;
	}else{
		$environment['PDO']['status'] = false;
	}
	
	$environment['pdo_mysql']['name'] = 'PDO Mysql driver found';
	if (in_array('pdo_mysql', $exts)) {
		$environment['pdo_mysql']['status'] = true;
	}else{
		$environment['pdo_mysql']['status'] = false;
	}
	
	$environment['hash']['name'] = 'Apache hash extention found';
	if (in_array('hash', $exts)) {
		$environment['hash']['status'] = true;
	}else{
		$environment['hash']['status'] = false;
	}
	
	$environment['hashing']['name'] = 'Hashing with tiger192 x 3';
	$algos = hash_algos();
	if (in_array('tiger192,3', $algos)) {
		$environment['hashing']['status'] = true;
	}else{
		$environment['hashing']['status'] = false;
	}
	
	$environment['write']['name'] = 'Write permission in sites directory';
	if(file_put_contents('sites/install.text', 'testing...')){
		$environment['write']['status'] = true;
	}else{
		$environment['write']['status'] = false;
	}
	
	$environment['read']['name'] = 'Read permission in sites directory';
	if(file_get_contents('sites/install.text')){
		$environment['read']['status'] = true;
	}else{
		$environment['read']['status'] = false;
	}
	
	$environment['delete']['name'] = 'Deletions in sites directory';
	if(unlink('sites/install.text')){
		$environment['delete']['status'] = true;
	}else{
		$environment['delete']['status'] = false;
	}
	
	$environment['download']['name'] = 'Use url fopen to download file';
	if(ini_get('allow_url_fopen')){
		$environment['download']['status'] = true;
	}else{
		$environment['download']['status'] = false;
	}
	
	$environment['zip']['name'] = 'PHP ZipArchive available';
	if(class_exists('ZipArchive')){
		$environment['zip']['status'] = true;
	}else{
		$environment['zip']['status'] = false;
	}
	return $environment;
}
function environment_html($pass, $name){
	if($pass){
		$display = 'PASS';
		$color = '00CC00';
		$symbol = 'fa fa-plus';
	}else{
		$display = 'FAIL';
		$color = 'BB0000';
		$symbol = 'fa fa-minus';
	}
	
	$code = <<<EOD
				<div>
					<span style="width:20px;"><i class="$symbol"></i></span>
					<span style="width:30px; color:#$color; font-weight:bold; overflow:hidden;"> $display </span>
					<span style="width:100%; megin:0 0 0 50px;">$name</span>
				</div>
EOD;
	return $code;
}
?>

<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title>Install Deepwater - Step <?=$step_num;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="core/assets/skeleton/stylesheets/base.css">
	<link rel="stylesheet" href="core/assets/skeleton/stylesheets/skeleton-fluid.css">
	<link rel="stylesheet" href="core/assets/jgrowl/jquery.jgrowl.min.css">
	<link rel="stylesheet" href="core/themes/tallsky/layout.css">
	<link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<link rel="shortcut icon" type="image/x-icon" href="core/themes/tallsky/favicon.ico?">
</head>
<body>
	<div class="container">	
		<div class="sixteen columns alpha omega nav menu" style="margin:0 0 14px 0;">
			<ul>
				<li>Deepwater Installation</li>
				<li>Step <?=$step_num;?> of 3</li>
			</ul>
		</div>
		<div class="sixteen columns">
		
		<? if($step_num == 1){ ?>
			<div class="six cloumns offset-by-one" style="padding-top:20px;">
				<h3>Environment Check</h3>
					<?=$env_html;?>
				<div class="twelve columns" style="margin:20px 0 0 0;">
				<form action="" method="post">
					<input type="submit" name="step_2" value="Next Step" class="button"<?=$disable_but;?> />
				</form>
				</div>
			</div>
		<? } ?>
			
		<? if($step_num == 2){ ?>
		<h3 class="offset-by-one">Configuration Setup</h3>
		<form method="post" action="">
			<div class="three columns offset-by-one" style="padding-top:20px;">
				<label for="host">Database Host</label>
				<input name="host" type="text" value="<?=$host;?>" />
	
				<label for="user">Database User</label>
				<input name="user" type="text" value="<?=$user;?>" />
					
				<label for="pass">Database Pass</label>
				<input name="pass" type="text" value="<?=$pass;?>" />
				
				<label for="name">Database Name</label>
				<input name="name" type="text" value="<?=$name;?>" />
					
				<label for="port">Database Port</label>
				<input name="port" type="text" value="<?=$port;?>" />
			</div>
			<div class="three columns" style="padding-top:20px;">
				<label for="salt">Hash Salt</label>
				<input name="salt" type="text" value="<?=$salt;?>" />
				
				<label for="base">Number Base</label>
				<input name="base" type="text" value="<?=$base;?>" />
				
				<label for="domain">Domain ex. http://deepwater.nid.io</label>
				<input name="domain" type="url" value="<?=$domain;?>" />
				
				<label for="path">Install Path ex. /deepwater</label>
				<input name="path" type="text" value="<?=$path;?>" />
			</div>
			<div class="ten columns" style="padding-top:20px;"> </div>
			<div class="eleven columns offset-by-five"><input class="button" name="step_2" value="Next Step" type="submit" /></div>
		</form>
		<? } ?>
		
		<? if($step_num == 3){ ?>
			<div class="six cloumns offset-by-one" style="padding-top:20px;">
				<h3>Admin Creation</h3>
				<div class="twelve columns" style="margin:20px 0 0 0;">
				<form action="" method="post">
					<label for="name">Name</label>
					<input name="name" type="text" />
					
					<label for="mail">Email</label>
					<input name="mail" type="email" />
					
					<label for="pass">Passphrase</label>
					<input name="pass" type="password" />
					
					<input type="submit" name="step_3" value="Finish Installation" class="button" />
				</form>
				</div>
			</div>
		<? } ?>
			
			
		</div>	
		<div class="sixteen columns alpha omega nav row remove-bottom" style="margin:20px 0 0 0;">
			<div class="sixteen columns tiny alpha omega"><a href="#">Deepwater CMS</a></div>
		</div>
	</div>
</body>
</html>