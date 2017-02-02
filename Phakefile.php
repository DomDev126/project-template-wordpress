<?php
require_once 'vendor/qobo/phake-builder/Phakefile.php';

/**
 * Run WP CLI batch file
 *
 * There might be a variety of WP CLI batches that
 * is ncessary for the app (install, update, content, etc),
 * so we standartize the location and naming convention.
 *
 * Each batch file is processed as a template befor execution
 * to make passing variables into there easier.
 *
 * @param string $name Name of batch file (e.g.: install)
 * @param array $app App data
 * @return void
 */
function runWPCLIBatch($name, $app)
{

    $src = 'etc/wp-cli.' . $name;
    $dst = $src . '.sh';

    $template = new \PhakeBuilder\Template($src);
    $placeholders = $template->getPlaceholders();
    $data = array();
    foreach ($placeholders as $placeholder) {
        $data[$placeholder] = getValue($placeholder, $app);

        // We really need wp-cli for this
        if ($placeholder == 'SYSTEM_COMMAND_WPCLI') {
            $data['SYSTEM_COMMAND_WPCLI'] = getValue($placeholder, $app) ?: './vendor/bin/wp --allow-root --path=webroot/wp';
        }
    }
    $bytes = $template->parseToFile($dst, $data);
    if (!$bytes) {
        throw new \RuntimeException("Failed to create batch file");
    }

    $parts = array();
    $parts[] = getValue('SYSTEM_COMMAND_SHELL', $app) ?: '/bin/sh';
    $parts[] = $dst;
    doShellCommand($parts);
    unlink($dst);
}

/**
 * Get project version
 *
 * @param array $app Application variables
 * @return string
 */
function getProjectVersion($app = null)
{
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
            } catch (\Exception $e) {
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

group('app', function () {

    desc('Install application');
    task('install', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: app:install (Install application)");
    });
    task('install', ':dotenv:create', ':dotenv:reload', ':file:process');
    task('install', ':mysql:database-create');
    // From here on, you can either import the full MySQL dump with find-replace...
    //task('install', ':mysql:database-import');
    //task('install', ':mysql:find-replace');
    // ... or have a fresh and clean install
    task('install', ':wordpress:install');
    task('install', ':wordpress:content');
    task('install', ':file:chmod');
    task('install', ':file:chown');
    task('install', ':file:chgrp');


    desc('Update application');
    task('update', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: app:update (Update application)");
    });
    task('update', ':dotenv:create', ':dotenv:reload', ':file:process', ':letsencrypt:symlink');
    //task('update', ':mysql:database-import');
    task('update', ':mysql:find-replace');
    task('update', ':wordpress:update');

    task('update', ':file:chmod');
    task('update', ':file:chown');
    task('update', ':file:chgrp');

    desc('Remove application');
    task('remove', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: app:remove (Update application)");
    });
    task('remove', ':mysql:database-drop');
    task('remove', ':dotenv:delete');

    //
    // Save version that we are deploying, both before and after
    //

    after(':builder:init', function ($app) {
        $version = getProjectVersion($app);
        // Save the version that we are deploying
        if (file_exists('build/version')) {
            rename('build/version', 'build/version.bak');
        }
        file_put_contents('build/version', $version);
    });

    after('install', function ($app) {
        $version = getProjectVersion($app);
        // Save the version that we have deployed
        if (file_exists('build/version.ok')) {
            rename('build/version.ok', 'build/version.ok.bak');
        }
        file_put_contents('build/version.ok', $version);
    });

    after('update', function ($app) {
        $version = getProjectVersion($app);
        // Save the version that we have deployed
        if (file_exists('build/version.ok')) {
            rename('build/version.ok', 'build/version.ok.bak');
        }
        file_put_contents('build/version.ok', $version);
    });
});

group('wordpress', function () {

    desc("Install WordPress");
    task('install', ':builder:init', function ($app) {
        printSeparator();
        printInfo("Task: wordpress:install (Install WordPress)");

        runWPCLIBatch('install', $app);
    });

    desc("Installation content WordPress");
    task('content', ':builder:init', function ($app) {
            printSeparator();
            printInfo("Task: wordpress:content (Installation content WordPress)");

            runWPCLIBatch('content', $app);
    });

    desc("Update content WordPress");
    task('update', ':builder:init', function ($app) {
            printSeparator();
            printInfo("Task: wordpress:update (Update content WordPress)");

            runWPCLIBatch('update', $app);
    });
});
