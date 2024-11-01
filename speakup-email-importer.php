<?php
/*
Plugin Name: SpeakUp! Email Petitions Importer
Plugin URI: http://ecolosites.fr/speakup-email-importer
Description: A Speakup! Email petition tool that provide CSV import functionnality.
Version: 1.3.0
Author: Bastho
Author URI: http://urbancube.fr
License: GPL2
*/

if ( is_admin() ) {
	add_action( 'plugins_loaded', 'dk_speakup_loadimporter');
	function dk_speakup_loadimporter(){
		
		// Check if Speakup! is activated
		if(function_exists('dk_speakup_meta_links')){
			load_plugin_textdomain( 'speakupimport', false, 'speakup-email-petitions-importer/languages' );
			include_once( dirname( __FILE__ ) . '/class.importer.php' );
			add_action( 'admin_enqueue_scripts', array( 'dk_Speakup_Import', 'scripts'));
			
			add_action('admin_menu', array( 'dk_Speakup_Import', 'menu'),1000);
		}
	}
}
