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
		<nav id="top-navigation-bar" class="navbar navbar-default text-center">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-10 col-sm-10 col-md-4 col-lg-3">
						<div class="navbar-logo text-center">
							<a href="<?php bloginfo( 'url' )?>">
								<img class="img-responsive"  src="<?php echo esc_attr( get_template_directory_uri() ); ?>/img/logo.png">
							</a>
						</div>
					</div>
					<div class="col-xs-2 col-sm-2">
						<div class="navbar-header">
							<button class="navbar-toggle">
							 <span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-8 col-lg-9">
						<div id="navbar" class="collapse navbar-collapse navbar-center">
							<?php wp_nav_menu( $header_nav );	?>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</header>
