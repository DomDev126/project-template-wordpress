<?php
/**
 * Helper functions
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

/**
 * Gets images by gategory slug when wp-media-library-categories plugin is enabled
 *
 * @param string $slug Slug name of a category image group.
 * @param int $no_of_images Number of images filter.
 * @return boolen/WP_Post Object $result.
 */
function get_images_by_gategory_slug( $slug, $no_of_images = 1 ) {

	$category = get_category_by_slug( $slug );

	if ( ! $category ) {
		return false;
	}

	$category_id = $category->term_id;

	$args = array(
		'post_type'   => 'attachment',
		'numberposts' => $no_of_images,
		'post_status' => null,
		'category'    => $category_id,
	);

	$result = get_posts( $args );

	return $result;
}
