<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Installing application");
	});
	task('install', ':git:pull');
	task('install', ':git:checkout');
	task('install', ':dotenv:create');
	task('install', ':composer:install');


	desc('Update application');
	task('update', ':builder:init', function($app) {
		printSeparator();
		printInfo("Updating application");
	});
	task('update', ':git:pull');
	task('update', ':git:checkout');
	task('update', ':dotenv:create');
	task('update', ':composer:install');


	desc('Remove application');
	task('remove', ':builder:init', function() {
		printSeparator();
		printInfo("Removing application");
	});
	task('remove', ':dotenv:delete');

});

# vi:ft=php
?>
