<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':git:pull', ':git:checkout', function() {
		printSeparator();
		printInfo("Installing application");
	});

	desc('Update application');
	task('update', ':git:pull', ':git:checkout', function() {
		printSeparator();
		printInfo("Updating application");
	});

	desc('Remove application');
	task('remove', function() {
		printSeparator();
		printInfo("Removing application");
	});

});

# vi:set ft=php
?>
