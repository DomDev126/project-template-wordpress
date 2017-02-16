SSL
===

Let's Encrypt
-------------

The recommended way to get a valid SSL certificate is to request one from [Let's Encrypt](https://letsencrypt.org/)].
You can do so by running the following commands from the root of the project:

```
# Get certificate
./vendor/bin/phake letsencrypt:certonly
# Create symlinks to certificate from project folder
./vendor/bin/phake letsencrypt:symlink
```

Make sure that:

* You have Let's Encrypt installed
* You have sufficient access rights to execute the above
* You have correct settings in project's `.env` file
* Let's Encrypt can connect back to your domain on port 80


Traditional
-----------

For the traditional alternative, you can follow the steps below.


Generate SSL certificate using the following:

```
$ cd etc/ssl
$ openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout nginx.key -out nginx.crt
```

Thanks to: https://www.digitalocean.com/community/tutorials/how-to-create-an-ssl-certificate-on-nginx-for-ubuntu-14-04

Remove password
===============

If you are using a not-self signed certificate, chances are, your key file is password protected.  You
should remove the password following the following steps:

```
$ openssl rsa -in nginx.key -out nginx.key.nopassword
$ mv nginx.key.nopassword nginx.key
```

Thanks to: http://mnx.io/blog/removing-a-passphrase-from-an-ssl-key/

If you don't remove the password, then you will be prompted to input it on every restart of the web server process.
This is not very practically, especially with automated deployments.
