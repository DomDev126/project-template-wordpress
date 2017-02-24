<?php
/**
 * Load parent theme
 *
 * @link http://codex.wordpress.org/Child_Themes
 *
 * @package WordPress
 * @subpackage Custom
 */

define( 'INCLUDES_DIR', get_stylesheet_directory_uri() . '/includes/' );

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );

/**
 * Enqueue the CSS of the parent theme
 *
 * @return void
 */
function enqueue_parent_theme_style() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-custom', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrap-min-css', INCLUDES_DIR . 'bootstrap-3.3.7-dist/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap-theme-min-css', INCLUDES_DIR . 'bootstrap-3.3.7-dist/css/bootstrap-theme.min.css' );
	wp_enqueue_style( 'font-awesome-min-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_style( 'smoke-min-css', INCLUDES_DIR . 'smoke-v3.1.1/css/smoke.min.css' );
}


add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_script' );

/**
 * Enqueue the JS of the parent theme
 *
 * @return void
 */
function enqueue_parent_theme_script() {
	wp_register_script( 'jquery-min-js', INCLUDES_DIR . 'jquery/jquery-1.12.4.min.js' , array(), false, true );
	wp_register_script( 'jquery-ui-min-js', INCLUDES_DIR . 'jquery-ui-1.12.1/jquery-ui.min.js', array(), false, true );
	wp_register_script( 'bootstrap-min-js', INCLUDES_DIR . 'bootstrap-3.3.7-dist/js/bootstrap.min.js', array(), false, true );
	wp_register_script( 'smoke-min-js', INCLUDES_DIR . 'smoke-v3.1.1/js/smoke.min.js', array(), false, true );

	wp_enqueue_script( 'jquery-min-js' );
	wp_enqueue_script( 'jquery-ui-min-js' );
	wp_enqueue_script( 'bootstrap-min-js' );
	wp_enqueue_script( 'smoke-min-js' );
}
