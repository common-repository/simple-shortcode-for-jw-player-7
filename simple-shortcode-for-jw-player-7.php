<?php
/**
 * Plugin Name: Simple shortcode for JW Player 7
 * Plugin URI: http://designs.dirlik.nl
 * Description: Plugin for JW player 7 that is compatible with the basic shortcodes from JW player 6 plugin
 * Version: 1.2.1
 * Author: Simon Dirlik
 * Author URI: http://designs.dirlik.nl
 * Text Domain: jw-player-7
 * Domain Path: /lang
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-jw-player.php';
	
	add_action( 'admin_menu' , array( 'JW_Player', 'menu' ) );
	add_shortcode( 'jwplayer', array( 'JW_Player', 'player_shortcode' ) );

	add_filter( 'mce_buttons', array( 'JW_Player', 'register_tinymce_button' ) );
	add_filter( 'mce_external_plugins', array( 'JW_Player', 'tinymce_button_javascript' ) );

