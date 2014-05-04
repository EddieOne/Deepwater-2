<?php
class configuration {
	// database connection 
	public $db_host='localhost';
	public $db_user='gloom_gw2';
	public $db_pass='7)!3o$}LgFmo';
	public $db_name='gloom_gw2';
	public $db_port=3306;
	public $hash_salt = 'mDlPJHIo6BxQpy7iqb1u';
	public $number_base = 'c7f12b396e58ad40';
	
	// common varibles
	public static $base_url = 'http://gloomwire.com';
	public static $install_path = '';
	public static $default_site = 'default';
	public static $admin_theme = 'tallsky';
	public static $site_status = 'online';
	public static $offline_message = 'We are undergoing maintenance. Please check back soon. ';
	public static $ban_message = 'You have been banned.';
}
?>