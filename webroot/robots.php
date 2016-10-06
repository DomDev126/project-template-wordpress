<?php
/**
 * This script allows for dynamic robots.txt rules,
 * based on the ALLOW_ROBOTS setting in the .env 
 * file.
 * 
 * Setting ALLOW_ROBOTS to false will disallow indexing
 * of anything on the site.  Setting it to true will
 * either allow everything or only what is defined in
 * the robots.txt file, if it exists and is readable.
 */
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

try {
	Dotenv::load(dirname(__DIR__));
}
catch (\Exception $e) {
	// If there is no .env file, we are probably on
	// a local/dev/test install with the project that
	// is not properly deployed.  No need to allow
	// robots indexing it.
	$allowRobots = false;
}

$allowRobots = (bool) getenv('ALLOW_ROBOTS');

// Switch MIME type to text/plain
header('Content-Type: text/plain'); 

// Allow indexing
if ($allowRobots) {
	$robotsFile = __DIR__ . DIRECTORY_SEPARATOR . 'robots.txt';
	// Use robots.txt rules if file exists and is readable
	if (file_exists($robotsFile) && is_readable($robotsFile)) {
		readfile($robotsFile);
	}
	// Allow indexing of everything if we can't read robots.txt
	else {
		echo "User-agent: *\n";
		echo "Disallow:\n";
	}
}
// Deny indexing
else {
	echo "User-agent: *\n";
	echo "Disallow: /\n";
}
