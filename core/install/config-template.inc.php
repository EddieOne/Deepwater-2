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
?>