<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':git:pull', ':git:checkout', ':builder:init', function() {
		printSeparator();
		printInfo("Installing application");
	});

	desc('Update application');
	task('update', ':git:pull', ':git:checkout', ':builder:init', function() {
		printSeparator();
		printInfo("Updating application");
	});

	desc('Remove application');
	task('remove', ':builder:init', function() {
		printSeparator();
		printInfo("Removing application");
	});

});

# vi:set ft=php
?>
