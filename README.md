project-template-wordpress
==========================

This is a template for the new project using WordPress.  It is heavily based
on the work done in [project-template](https://github.com/QoboLtd/project-template).

Install
-------

When starting a new PHP project, do the following:

```
mkdir new-project
cd new-project
git init
git remote add template git@github.com:QoboLtd/project-template-wordpress.git
git remote update
git merge template/master
composer install
./vendor/bin/phake dotenv:create DB_NAME=wordpress
./vendor/bin/phake app:install
```

DB_NAME, the name of the database to use, is the only setting which is required.  The
rest is being figured out automatically, but you can easily adjust them.  Have a look
at .env.example file for defaults.

Test
----

Now that you have the project template installed, check that it works
before you start working on your changes.  Fire up the PHP web server:

```
php -S localhost:8000
```

In your browser navigate to [http://localhost:8000](http://localhost:8000).  
You should see the standard ```phpinfo()``` page.  If you do, all parts 
are in place.

Usage
-----

Now you can develop your PHP project as per usual, but with the following
advantages:

* Per-environment configuration using .env file, which is ignored by git
* Powerful build system (phake-builder) integrated
* Composer integrated with vendor/ folder added to .gitignore .
* PHPUnit integrated with tests/ folder and an example unit test.
* Sensible defaults for best practices - favicon.ico, robots.txt, GPL, etc
* Several WordPress plugins pre-installed (not activated by default though)
* TwentyFourteen WordPress theme pre-installed (not activated, but used by default by WordPress)
* Extran WordPress plugins when composer install/update --dev executed

For example, you can easily automate the build process of your application
by modifying the included Phakefile.  Run the following command to examine
available targets:

```
./vendor/bin/phake -T
```

As you can see, there are already placeholders for app:install, app:update,
and app:remove.  You can populate these, remove them or add more, of
course.

Here is how to run your unit tests:

```
./vendor/bin/phpunit --coverage-text --colors tests/
```

There's an example one for you, so now you have no excuse NOT to write
them.

Configurations
--------------

Plugin - Compress PNG for WP (Using TinyPNG API)
This plugin requires an API key from TinyPNG. You can set your key in .env.example file using parameter TINYPNG_API_KEY. A default valid API key has already been added to the template but due to a limited number of requests allowed per key, each project should be using its own key. You can get an API key at https://tinypng.com/developers (one key per email address).
