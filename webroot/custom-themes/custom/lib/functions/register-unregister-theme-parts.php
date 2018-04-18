<?php
/**
 * Register / Unregister certain theme parts
 *
 * @package WordPress
 * @subpackage Custom
 */

add_filter( 'theme_page_templates', 'unregister_page_templates' );

/**
 * Unregisters not needed widgets from parent theme
 *
 * @param array $page_templates Parent theme page templates.
 * @return array
 */
function unregister_page_templates( $page_templates ) {
	unset( $page_templates['page-homepage.php'] );
	unset( $page_templates['page-sidebar-left.php'] );
	unset( $page_templates['page-sidebar-right.php'] );
	unset( $page_templates['page-items-list.php'] );
	unset( $page_templates['page-news.php'] );
	unset( $page_templates['page-contact.php'] );
	unset( $page_templates['page-both-sidebar.php'] );
	unset( $page_templates['page-gallery.php'] );
	unset( $page_templates['page-no-sidebars.php'] );
	return $page_templates;
}

add_action( 'widgets_init', 'unregister_default_sidebars', 11 );

/**
 * Unregisters not needed sidebar widgets from parent theme
 *
 * @return void
 */
function unregister_default_sidebars() {
	unregister_sidebar( 'under-footer-widget-1' );
	unregister_sidebar( 'under-footer-widget-2' );
	unregister_sidebar( 'footer-widget-1' );
	unregister_sidebar( 'footer-widget-2' );
	unregister_sidebar( 'footer-widget-3' );
	unregister_sidebar( 'footer-widget-4' );
	unregister_sidebar( 'sidebar-left' );
	unregister_sidebar( 'sidebar-right' );
}

/**
 * Unregisters wp emojicons
 *
 * @return void
 */
function disable_wp_emojicons() {

	// all actions related to emojis.
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	// filter to remove TinyMCE emojis.
	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

add_action( 'init', 'disable_wp_emojicons' );

/**
 * Removes 'wpemoji' from the TinyMCE plugin list.
 *
 * @param  array $plugins TinyMCE plugins.
 * @return array of filtered out plugins.
 */
function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, [ 'wpemoji' ] );
	}

	return [];
}


/**
 * Disables contact form 7 inline styles
 *
 * @return void
 */
function disable_cf7_inline_styles() {
	remove_action( 'wp_head', 'cf7bs_inline_styles' );
}

add_action( 'init', 'disable_cf7_inline_styles' );

