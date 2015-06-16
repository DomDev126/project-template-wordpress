project-template
================

This is a template for the new project.

Install
-------

When starting a new PHP project, do the following:

```bash
# Initiate the new work space
mkdir new-project
cd new-project
git init
# Kick off the project (this is needed for --squash merge later)
touch README.md
git add README.md
git commit -m "Initial commit"
# Get project-template
git remote add template https://github.com/QoboLtd/project-template.git
git remote update
# Merge latest tag (or use 'template/master' instead)
git merge --squash $(git tag | tail -n 1)
git commit -m "Merged project-template ($(git tag | tail -n 1))"
# Finalize the setup
composer install
./vendor/bin/phake dotenv:create
```

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

* Per-environment configuration using ```.env``` file, which is ignored by git
* Powerful build system ([phake-builder](https://github.com/QoboLtd/phake-builder)) integrated
* Composer integrated with ```vendor/``` folder added to ```.gitignore``` .
* PHPUnit integrated with ```tests/``` folder and an example unit test.
* Sensible defaults for best practices - favicon.ico, robots.txt, MySQL dump, Nginx configuration, GPL, etc.

For example, you can easily automate the build process of your application
by modifying the included ```Phakefile```.  Run the following command to examine
available targets:

```
./vendor/bin/phake -T
```

As you can see, there are already some placeholders for your application's build
process.  By default, it is suggested that you have these:

* ```app:install``` - for installation process of your application,
* ```app:update``` - for the update process of the already installed application, and
* ```app:remove``` - for the application removal process and cleanup.

You can, of course, add your own, remove these, or change them any way you want.  Have a look at
[phake-builder](https://github.com/QoboLtd/phake-builder) documentation for more information on how
to use these targets and pass runtime configuration parameters.

Here is how to run your unit tests:

```
./vendor/bin/phpunit --coverage-text --colors tests/
```

There's an example one for you, so now you have no excuse NOT to write
them.

