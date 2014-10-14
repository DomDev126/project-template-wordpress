<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':builder::init', function($app) {
		printSeparator();
		printInfo("Installing application");
		task('install', ':git:pull');
		task('install', ':git:checkout');
	});

	desc('Update application');
	task('update', ':builder:init', function($app) {
		printSeparator();
		printInfo("Updating application");
		task('install', ':git:pull');
		task('install', ':git:checkout');
	});

	desc('Remove application');
	task('remove', ':builder:init', function() {
		printSeparator();
		printInfo("Removing application");
	});

});

# vi:set ft=php
?>
