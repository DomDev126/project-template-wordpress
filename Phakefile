<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Installing application");
	});
	//task('install', ':git:pull', ':git:checkout');
	task('install', ':composer:install');
	task('install', ':dotenv:create', ':dotenv:reload', ':file:process');
	task('install', ':mysql:database-create');
	// From here on, you can either import the full MySQL dump with find-replace...
	//task('install', ':mysql:database-import');
	//task('install', ':mysql:find-replace');
	// ... or have a fresh and clean install
	task('install', ':wordpress:install');


	desc('Update application');
	task('update', ':builder:init', function($app) {
		printSeparator();
		printInfo("Updating application");
	});
	task('update', ':git:pull', ':git:checkout');
	task('update', ':composer:install');
	task('update', ':dotenv:create', ':dotenv:reload', ':file:process');
	task('update', ':mysql:database-import');
	task('update', ':mysql:find-replace');


	desc('Remove application');
	task('remove', ':builder:init', function($app) {
		printSeparator();
		printInfo("Removing application");
	});
	task('remove', ':mysql:database-drop');
	task('remove', ':dotenv:delete');

});

group('wordpress', function() {

	desc("Install WordPress");
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Installing WordPress");

		$src = 'etc/wp-cli.install';
		$dst = 'etc/wp-cli.install.sh';

		$template = new \PhakeBuilder\Template($src);
		$placeholders = $template->getPlaceholders();
		$data = array();
		foreach ($placeholders as $placeholder) {
			$data[$placeholder] = getValue($placeholder, $app);
			
			// We really need wp-cli for this
			if ($placeholder == 'SYSTEM_COMMAND_WPCLI') {
				$data['SYSTEM_COMMAND_WPCLI'] = getValue($placeholder, $app) ?: './vendor/bin/wp';
			}
		}
		$bytes = $template->parseToFile($dst, $data);
		if (!$bytes) {
			throw new \RuntimeException("Failed to create batch file");
		}

		doShellCommand('/bin/sh ' . $dst);
		#unlink($dst);
		
	});
	
});

# vi:ft=php
?>
