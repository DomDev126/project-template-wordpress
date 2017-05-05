<?php
/**
 * The Header for Qobo Generic theme
 *
 * This template is now based on Bootstrap starter template
 * More info: http://getbootstrap.com/examples/jumbotron/
 *
 * @package Qobo Generic Wordpress Theme
 */

$header_nav = [
	'menu' => 'Header',
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
<!DOCTYPE html>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php wp_title( '', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header id="top-header">
		<nav id="top-navigation-bar" class="navbar navbar-inverse navbar-fixed-top">
		  <div class="container">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <a class="navbar-brand" href="#">Qobo Generic Theme</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse navbar-right">
				<?php wp_nav_menu( $header_nav );	?>
			</div>
		  </div>
		</nav>
	</header>
