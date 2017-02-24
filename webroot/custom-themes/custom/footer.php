<?php
/**
 * The template for displaying the footer
 *
 * This template is now based on Bootstrap starter template
 * More info: http://getbootstrap.com/examples/jumbotron/
 *
 * @package Qobo Generic Wordpress Theme
 */

$footer_nav = [
	'theme_location' => 'footer-menu',
	'menu' => 'Footer Menu',
	'container' => '',
	'container_class' => '',
	'menu_class' =>
	'nav navbar-nav ',
	'depth' => 0,
	'fallback_cb' =>
	'qobogt_wp_bootstrap_navwalker::fallback',
	'walker' => new qobogt_wp_bootstrap_navwalker(),
];

?>

<footer id="footer">
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<nav id="footer-navigation-bar" class="navbar-right">
					<?php wp_nav_menu( $footer_nav ); ?>
				</nav>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-1"><?php the_widget( 'QBDEVBY_Widget' ); ?></div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
