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
