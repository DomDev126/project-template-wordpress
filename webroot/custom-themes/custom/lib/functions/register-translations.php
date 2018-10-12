<?php
/**
 * Register Custom Translation Files
 *
 * @package WordPress
 * @subpackage Custom
 */

define( 'THEME_LANG_PATH', dirname( dirname( dirname( __DIR__ ) ) ) . DIRECTORY_SEPARATOR . 'custom' . DIRECTORY_SEPARATOR . 'languages' );

/**
 * Loads the theme's translated strings files based on their textdomain.
 *
 * @param string $path Path to the directory containing translation file.
 */
function register_textdomain( string $path ) : void {

	$text_domains = [ 'custom-theme' ];
	foreach ( $text_domains as $domain ) {
		load_child_theme_textdomain( $domain , $path );
	}
}

/**
 * Registers all translation PO files which are located within the $theme_language_path.
 */
function register_child_theme_textdomains() : void {

	$dir = new DirectoryIterator( THEME_LANG_PATH );

	if ( ! empty( $dir ) ) {
		foreach ( $dir as $fileinfo ) {
			if ( $fileinfo->isDir() && ! $fileinfo->isDot() ) {
				register_textdomain( THEME_LANG_PATH . DIRECTORY_SEPARATOR . $fileinfo->getFilename() );
			}
		}
		register_textdomain( THEME_LANG_PATH );
	}
}

add_action( 'init', 'register_child_theme_textdomains' );

/**
 * Change default locate
 *
 * @param string $locale Wordpress default locate.
 * @return string $locale
 */
function wpsx_redefine_locale( string $locale ) : string {

	if ( isset( $_GET['lang'] ) ) {
		return esc_attr( $_GET['lang'] );
	}
	return $locale;

}
add_filter( 'locale','wpsx_redefine_locale',10 );
