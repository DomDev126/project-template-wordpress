<?php
/**
 * Load parent theme
 *
 * @link http://codex.wordpress.org/Child_Themes
 *
 * @package WordPress
 * @subpackage Custom
 */

add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );

/**
 * Enqueue the CSS of the parent theme
 *
 * @return void
 */
function enqueue_parent_theme_style() {
	wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-custom', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_style( 'bootstrap-min-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap-theme-min-css', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' );
	wp_enqueue_style( 'font-awesome-min-css', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
}


add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_script' );

/**
 * Enqueue the JS of the parent theme
 *
 * @return void
 */
function enqueue_parent_theme_script() {
	wp_register_script( 'jquery-min-js', '//code.jquery.com/jquery-1.12.4.min.js', array(), false, true );
	wp_register_script( 'jquery-ui-min-js', '//code.jquery.com/ui/1.12.1/jquery-ui.min.js', array(), false, true );
	wp_register_script( 'bootstrap-min-js', '//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', array(), false, true );

	wp_enqueue_script( 'jquery-min-js' );
	wp_enqueue_script( 'jquery-ui-min-js' );
	wp_enqueue_script( 'bootstrap-min-js' );
}
