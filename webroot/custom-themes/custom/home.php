<?php
/**
 * Template Name: Homepage
 * The template for displaying the homepage
 *
 * This template is now based on Bootstrap starter template
 * More info: http://getbootstrap.com/examples/jumbotron/
 *
 * @package Qobo Generic Wordpress Theme
 */

get_header();
?>

<div class="jumbotron">
	<div class="container">
		<a href="<?php bloginfo( 'url' )?>"><img src="<?php echo esc_attr( get_template_directory_uri() ); ?>/img/logo.png" alt="Company logo"></a>
		<p>This is a template for a simple marketing or informational website. It includes a large callout called a jumbotron and three supporting pieces of content. Use it as a starting point to create something more unique.</p>
	</div>
	<div class="container">
	<div class="row">
		<div class="col-md-6">
			<h3>Font awesome is included</h3>
			<i class="fa fa-camera-retro fa-lg"></i> fa-lg
			<i class="fa fa-camera-retro fa-2x"></i> fa-2x
			<i class="fa fa-camera-retro fa-3x"></i> fa-3x
			<i class="fa fa-camera-retro fa-4x"></i> fa-4x
			<i class="fa fa-camera-retro fa-5x"></i> fa-5x
		</div>
	</div>
	</div>
</div>

<div class="container">
	<div class="row">
		<div class="col-md-4">
			<h2>Heading</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
			<p><a class="btn btn-default" href="#" role="button">View details</a></p>
		</div>
		<div class="col-md-4">
			<h2>Heading</h2>
			<p>Donec id elit non mi porta gravida at eget metus. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus. Etiam porta sem malesuada magna mollis euismod. Donec sed odio dui. </p>
			<p><a class="btn btn-default" href="#" role="button">View details</a></p>
		</div>
		<div class="col-md-4">
			<h2>Heading</h2>
			<p>Donec sed odio dui. Cras justo odio, dapibus ac facilisis in, egestas eget quam. Vestibulum id ligula porta felis euismod semper. Fusce dapibus, tellus ac cursus commodo, tortor mauris condimentum nibh, ut fermentum massa justo sit amet risus.</p>
			<p><a class="btn btn-default" href="#" role="button">View details</a></p>
		</div>
	</div>
</div>

<?php
get_footer();
