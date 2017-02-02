<?php
/**
 * Require login
 *
 * @package Custom
 */

/**
 * Require login
 *
 * Check the `REQUIRE_LOGIN` environment variable, and if it set to
 * true, then redirect non-authenticated users to the login page.
 *
 * @return void
 */
function redirect_non_logged_users_to_login_page() {
	if ( getenv( 'REQUIRE_LOGIN' ) ) {
		global $pagenow;
		if ( ! is_user_logged_in() && 'wp-login.php' !== $pagenow ) {
			wp_safe_redirect( wp_login_url() );
		}
	}
}
add_action( 'wp', 'redirect_non_logged_users_to_login_page' );
