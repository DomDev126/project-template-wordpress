SSL
===

Generate SSL certificate using the following:

```
$ cd etc/ssl
$ openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout nginx.key -out nginx.cert
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
