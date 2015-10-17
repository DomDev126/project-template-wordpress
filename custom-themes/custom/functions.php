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
?>
