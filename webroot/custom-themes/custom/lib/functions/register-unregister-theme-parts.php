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
