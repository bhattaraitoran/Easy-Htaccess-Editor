<?php
/*
Plugin Name: Easy Htaccess Editor
Plugin URI: https://github.com/bhattaraitoran/Easy-Htaccess-Editor
Description: Edits wordpress htaccess file from admin panel
Version: 1.0
Text Domain: easy-htaccess-editor
Domain Path: /lang/
Author: Toran Bhattarai
Author URI: https://github.com/bhattaraitoran/
License: GPLv2 or later
*/

/*
Easy Htaccess Editor is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Easy Htaccess Editor is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Regenerate Thumbnails. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

if (!defined('ABSPATH')) die('Silence is golden!');

if( ! is_admin() ){
	return;
}

class Easy_Htaccess_Editor{

	private static $instance;

	/**
	 * Actions setup
	 */
	public function __construct() { 

		add_action( 'plugins_loaded', array( $this, 'constants' ), 2 );
		add_action( 'plugins_loaded', array( $this, 'locale' ), 3 );
		add_action( 'plugins_loaded', array( $this, 'includes' ), 4 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ), 5 );
		add_action( 'admin_menu' , array( $this, 'menu' )  , 6 );
	}


	/* 
	 * path/url constant configuration
	 */
	public function constants(){ 

		define( 'EHE_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'EHE_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'EHE_SITEURL', get_site_url().'/' );
		define( 'EHE_CONTENT_URL', EHE_SITEURL.'wp-content' );
		define( 'EHE_PLUGIN_URL', EHE_CONTENT_URL.'/plugins' );
	}


	/**
	 * String translations
	 */
	public function locale(){

		load_plugin_textdomain( 'easy-htaccess-editor', false, 'easy-htaccess-editor/languages' );
	}


	/**
	 * Include required files
	 */
	public function includes(){

		require_once( EHE_DIR . 'inc/class/handle.php' );
		require_once( EHE_DIR . 'inc/class/notice.php' );
		require_once( EHE_DIR . 'inc/class/form.php' );
		
	}



	/**
     * loads javscript and css files in admin section.
     */
	public function enqueue(){

		wp_enqueue_style('ehe-style', EHE_URI.'style/ehe-style.css' );
	}



	/**
     * Adds menu on wordpress admin panel
     */
	public function menu(){

		if( current_user_can( 'activate_plugins' ) ){

				add_menu_page( __( 'Easy Htaccess Editor','easy-htaccess-editor' ), 'Htaccess', 'activate_plugins', 'easy-htaccess-editor',  function(){ $this->ehe_edit_page(); } , '' );
				add_submenu_page( 'easy-htaccess-editor' , __( 'Edit Htaccess','easy-htaccess-editor' ),'Edit Htaccess', 'activate_plugins', 'easy-htaccess-editor',  function(){ $this->ehe_edit_page(); } );
				add_submenu_page( 'easy-htaccess-editor', __('Backup', 'easy-htaccess-editor'),__('Backup', 'easy-htaccess-editor'), 'activate_plugins', 'easy-htaccess-editor-backup', function(){ $this->ehe_backup_page(); } );

			}
	}

	/*
	 * Htaccess edit page
	 */
	public function ehe_edit_page(){

		require_once( EHE_DIR . 'inc/page/edit.php' );
		
	}

	/*
	 * Htaccess backup page
	 */
 	public function ehe_backup_page(){

		require_once( EHE_DIR . 'inc/page/backup.php' );
		
	}

	/*
	 * plugin instance
	 */
	public static function get_instance() {

		if ( ! self::$instance )
			self::$instance = new self;

		return self::$instance;
	}

	
}

	function EHE_plugin_load() {

		
			return Easy_Htaccess_Editor::get_instance();

	}
	add_action('plugins_loaded', 'EHE_plugin_load', 1 );