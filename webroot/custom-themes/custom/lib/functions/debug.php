<?php
/**
 * Debug
 *
 * @package Custom
 */

/**
 * Debug variable
 *
 * Check if the WP_DEBUG is enabled, and if so, print out the
 * given variable.
 *
 * @param mixed $var Variable to print out.
 * @return void
 */
function debug( $var ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		echo '<pre>';
		// @codingStandardsIgnoreStart
		print_r( $var );
		// @codingStandardsIgnoreEnd
		echo '</pre>';
	}
}
