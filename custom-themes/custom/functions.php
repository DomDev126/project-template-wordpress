<?php
	//Theme's images URI
	if (!defined('IMAGES_URI')) {
		define('IMAGES_URI', get_stylesheet_directory_uri() . '/images/');
	}

	//Theme's functions directory
	if (!defined('THEME_LIB_DIR')) {
		define('THEME_LIB_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR);
	}

	/**
	 * Load all functions which are placed in theme's folder
	 *
	 * @return void
	 */
	function loadIncludes($dir){
		$it = new RecursiveDirectoryIterator($dir);
		$it = new RecursiveIteratorIterator($it);
		$it = new RegexIterator($it, '#.php$#');
		foreach ($it as $include) {
			if ($include->isReadable()) {
				require_once($include->getPathname());
			}
		}
	}

	loadIncludes(THEME_LIB_DIR);
?>
