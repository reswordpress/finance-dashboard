<?php
/*
Plugin Name: Financieel dashboard
Plugin Script: finance.php
Description: Financieel dashboard
Version: 2.0
Author: Houke de Kwant
Author URI: http://thearthunters.com
License: GPLv2 or later
GitHub Plugin URI: https://github.com/houke/finance-dashboard
GitHub Branch: master
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

//Constants
$prefix  = 'FD_';
$version = '2.0';

//Define variables
$cpg_constants = array(
	'VERSION'           => $version,
	'NAME'				=> 'finance-dashboard',
	'BASENAME'          => plugin_basename( __FILE__ ),
	'DIR'               => plugin_dir_path( __FILE__ ),
	'URL'               => plugin_dir_url( __FILE__ ),
	'FILE'              => __FILE__
);

foreach ( $cpg_constants as $const_name => $constant_val ) {
	if ( ! defined( $prefix . $const_name ) ) {
		define( $prefix . $const_name, $constant_val );
	}
}

//Import supporting libraries
require_once FD_DIR.'inc/fd_admin_columns.php';
require_once FD_DIR.'inc/fd_admin_scripts.php';
require_once FD_DIR.'inc/fd_add_fields.php';
require_once FD_DIR.'inc/fd_save_fields.php';
require_once FD_DIR.'inc/fd_post_types.php';
require_once FD_DIR.'inc/fd_setting_pages.php';
require_once FD_DIR.'inc/fd_load_templates.php';
