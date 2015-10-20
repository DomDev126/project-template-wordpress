<?php
	//Theme's images URI
	if (!defined('IMAGES_URI')) {
		define('IMAGES_URI', get_stylesheet_directory_uri() . '/images/');
	}
	
	//Theme's functions directory
	if (!defined('FUNCTIONS_DIR')) {
		define('FUNCTIONS_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'functions' . DIRECTORY_SEPARATOR);
	}
	
	/**
	 * Load all includes which are placed in theme's folder
	 *
	 * @return void
	 */
	if ( ! function_exists( 'loadFunctions' ) ):
		function loadFunctions() {
			$it = new DirectoryIterator(FUNCTIONS_DIR);
			$it = new RegexIterator($it, '#.php$#');
			foreach ($it as $include) {
				if ($include->isReadable()) {
					require_once($include->getPathname());
				}
			}
		}
		loadFunctions();
	endif;
	
	//Theme's widgets directory
	if (!defined('WIDGET_DIR')) {
		define('WIDGET_DIR', __DIR__ . DIRECTORY_SEPARATOR .'widgets' . DIRECTORY_SEPARATOR);
	}
	
	/**
	 * Load all widgets which are placed in theme's folder
	 *
	 * @return void
	 */
	if ( ! function_exists( 'loadWidgets' ) ):
		function loadWidgets() {
			$it = new DirectoryIterator(WIDGET_DIR);
			$it = new RegexIterator($it, '#.php$#');
			foreach ($it as $widget) {
				if ($widget->isReadable()) {
					require_once($widget->getPathname());
				}
			}
		}
		loadWidgets();
	endif;
	
	//Theme's shortcodes directory
	if (!defined('SHORTCODES_DIR')) {
		define('SHORTCODES_DIR', __DIR__ . DIRECTORY_SEPARATOR .'shortcodes' . DIRECTORY_SEPARATOR);
	}
	
	/**
	 * Load all widgets which are placed in theme's folder
	 *
	 * @return void
	 */
	if ( ! function_exists( 'loadShortcodes' ) ):
		function loadShortcodes() {
			$it = new DirectoryIterator(SHORTCODES_DIR);
			$it = new RegexIterator($it, '#.php$#');
			foreach ($it as $shortcode) {
				if ($shortcode->isReadable()) {
					require_once($shortcode->getPathname());
				}
			}
		}
		loadShortcodes();
	endif;
	
	//Theme's includes directory
	if (!defined('INCLUDES_DIR')) {
		define('INCLUDES_DIR', __DIR__ . DIRECTORY_SEPARATOR .'includes' . DIRECTORY_SEPARATOR);
	}
	
	/**
	 * Load all widgets which are placed in theme's folder
	 *
	 * @return void
	 */
	if ( ! function_exists( 'loadIncludes' ) ):
		function loadIncludes() {
			$it = new DirectoryIterator(INCLUDES_DIR);
			$it = new RegexIterator($it, '#.php$#');
			foreach ($it as $include) {
				if ($shortcode->isReadable()) {
					require_once($include->getPathname());
				}
			}
		}
		loadIncludes();
	endif;
	
	//Theme's includes directory
	if (!defined('CLASSES_DIR')) {
		define('CLASSES_DIR', __DIR__ . DIRECTORY_SEPARATOR .'classes' . DIRECTORY_SEPARATOR);
	}
	
	/**
	 * Load all classes which are placed in theme's folder
	 *
	 * @return void
	 */
	if ( ! function_exists( 'loadClasses' ) ):
		function loadClasses() {
			$it = new DirectoryIterator(CLASSES_DIR);
			$it = new RegexIterator($it, '#.php$#');
			foreach ($it as $class) {
				if ($class->isReadable()) {
					require_once($class->getPathname());
				}
			}
		}
		loadClasses();
	endif;
?>
