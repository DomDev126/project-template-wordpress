<?php
require_once 'vendor/qobo/phake-builder/Phakefile.php';

group('app', function() {

	desc('Install application');
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Task: app:install (Install application)");
	});
	task('install', ':dotenv:create', ':dotenv:reload', ':file:process');


	desc('Update application');
	task('update', ':builder:init', function($app) {
		printSeparator();
		printInfo("Task: app:update (Update application)");
	});
	task('update', ':dotenv:create', ':dotenv:reload', ':file:process', ':letsencrypt:symlink');


	desc('Remove application');
	task('remove', ':builder:init', function($app) {
		printSeparator();
		printInfo("Task: app:remove (Update application)");
	});
	task('remove', ':dotenv:delete');

});
