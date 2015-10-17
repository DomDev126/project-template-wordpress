<?php
	# Read more about child theme development at:
	# http://codex.wordpress.org/Child_Themes
	add_action( 'wp_enqueue_scripts', 'enqueue_parent_theme_style' );
	function enqueue_parent_theme_style() {
		wp_enqueue_style('parent-style', get_template_directory_uri().'/style.css' );
	}

	//Theme's images URI
	if (!defined('IMAGES_URI')) {
		define('IMAGES_URI', get_stylesheet_directory_uri() . '/images/');
	}
?>
