<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Installing application");
	});
	task('install', ':git:pull', $app);
	task('install', ':git:checkout', $app);
	task('install', ':composer:install', $app);


	desc('Update application');
	task('update', ':builder:init', function($app) {
		printSeparator();
		printInfo("Updating application");
	});
	task('update', ':git:pull', $app);
	task('update', ':git:checkout', $app);
	task('update', ':composer:install', $app);


	desc('Remove application');
	task('remove', ':builder:init', function() {
		printSeparator();
		printInfo("Removing application");
	});

});

# vi:ft=php
?>
