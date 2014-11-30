<?php
require_once 'vendor/qobo/phake-builder/Phakefile';

group('app', function() {

	desc('Install application');
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Installing application");
	});
	task('install', ':git:pull', ':git:checkout');
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

		$wp = getValue('SYSTEM_COMMAND_WPCLI', $app) ?: './vendor/bin/wp';

		$parts = array();
		$parts[] = $wp;
		$parts[] = 'core install';
		$parts[] = '--url=' . requireValue('WP_URL', $app);
		$parts[] = '--title=' . requireValue('WP_TITLE', $app);
		$parts[] = '--admin_user=' . requireValue('WP_ADMIN_USER', $app);
		$parts[] = '--admin_password=' . requireValue('WP_ADMIN_PASS', $app);
		$parts[] = '--admin_email=' . requireValue('WP_ADMIN_EMAIL', $app);

		doShellCommand($parts);
	});
	
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Removing default content");

		$wp = getValue('SYSTEM_COMMAND_WPCLI', $app) ?: './vendor/bin/wp';

		$parts = array();
		$parts[] = $wp;
		$parts[] = 'comment delete 1 --force';
		doShellCommand($parts);
		
		$parts = array();
		$parts[] = $wp;
		$parts[] = 'post delete 2 --force';
		doShellCommand($parts);
		
		$parts = array();
		$parts[] = $wp;
		$parts[] = 'post delete 1 --force';
		doShellCommand($parts);

	});
	
	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Setup friendly URLs");

		$wp = getValue('SYSTEM_COMMAND_WPCLI', $app) ?: './vendor/bin/wp';

		$parts = array();
		$parts[] = $wp;
		$parts[] = 'rewrite structure';
		$parts[] = '"/%year%/%monthnum%/%day%/%postname%/"';
		doShellCommand($parts);
	});

	task('install', ':builder:init', function($app) {
		printSeparator();
		printInfo("Activate plugins");

		$wp = getValue('SYSTEM_COMMAND_WPCLI', $app) ?: './vendor/bin/wp';

		$parts = array();
		$parts[] = $wp;
		$parts[] = 'plugin list --status=inactive --field=name --format=json';
		$plugins = json_decode(doShellCommand($parts, null, true));

		foreach ($plugins as $plugin) {
			printInfo("Activating plugin $plugin");
			$parts = array();
			$parts[] = $wp;
			$parts[] = 'plugin activate';
			$parts[] = $plugin;
			doShellCommand($parts);
		}
		
	});
});

# vi:ft=php
?>
