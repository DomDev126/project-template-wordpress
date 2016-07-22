<?php
require_once 'vendor/qobo/phake-builder/Phakefile.php';

function getProjectVersion($app = null) {
	$result = null;

	// If we have $app variables, try to figure out version
	if (!empty($app)) {
		// Use GIT_BRANCH variable ...
		$result = getValue('GIT_BRANCH', $app);
		// ... if empty, use git hash
		if (empty($result)) {
			try {
				$git = new \PhakeBuilder\Git(getValue('SYSTEM_COMMAND_GIT', $app));
				$result = doShellCommand($git->getCurrentHash(), null, true);
			}
			catch (\Exception $e) {
				// ignore
			}
		}
	}

	// ... if empty, use default
	if (empty($result)) {
		$result = 'Unknown';
	}

	return $result;
}

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

	//
	// Save version that we are deploying, both before and after
	//

	after(':builder:init', function($app) {
		$version = getProjectVersion($app);
		// Save the version that we are deploying
		if (file_exists('build/version')) {
			rename('build/version', 'build/version.bak');
		}
		file_put_contents('build/version', $version);
	});

	after('install', function($app) {
		$version = getProjectVersion($app);
		// Save the version that we have deployed
		if (file_exists('build/version.ok')) {
			rename('build/version.ok', 'build/version.ok.bak');
		}
		file_put_contents('build/version.ok', $version);
	});

	after('update', function($app) {
		$version = getProjectVersion($app);
		// Save the version that we have deployed
		if (file_exists('build/version.ok')) {
			rename('build/version.ok', 'build/version.ok.bak');
		}
		file_put_contents('build/version.ok', $version);
	});

});
