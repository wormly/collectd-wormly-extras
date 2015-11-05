Monitoring PHP-FPM
==========

We utilize the `curl_json` collectd plugin to perform data collection from PHP-FPM (PHP FastCGI Process Manager), so ensure that your collectd installation has this plugin available:

```Shell
yum install collectd-wormly-curl-json
apt-get install collectd-wormly-curl-json
```

Then, add the following configuration directive to your PHP-FPM config to expose the PHP-FPM status page:

```Shell
# Edit your PHP-FPM config file, e.g. with vi /etc/php-fpm.d/www.conf
# Add this line
pm.status_path = /fpm_status.php
```

Don't forget to restart or send `SIGUSR2` to your PHP-FPM daemon to refresh its configuration.

Now, create an empty `fpm_status.php` file in the appropriate WWW document root (only if you're using Apache):

```Shell
touch /var/www/html/fpm_status.php
```

Finally, restrict access to localhost:

#### With Nginx + FastCGI:

```Nginx
location /fpm_status.php {
    access_log off;
    allow 127.0.0.1;
    deny all;
    include fastcgi_params;
    fastcgi_pass 127.0.0.1:9000;
}
```

#### With Apache:

```ApacheConf
<Location "/fpm_status.php">
    Order deny,allow
    Deny from all
    Allow from 127.0.0.1
</Location>
```

Test that you can request the status page:

```Shell
curl http://127.0.0.1/fpm_status.php
```

Now you can proceed with collectd configuration.  Place `monitor-php-fpm.conf` in your collectd additional config directory; for example `/opt/wormly/collectd/etc/collectd.d/`.

Test your configuration:

```Shell
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```


Multiple Instances of PHP-FPM 
=========

You may have multiple instances of PHP-FPM running on a single host (e.g. if you have multiple PHP-FPM pools or multiple web servers for different purposes). Accordingly, you will wish to identify metrics for each instance separately. To achieve this, simply duplicate the `<url>` block and change the URL and Instance values to suit your configuration:

```ApacheConf
<url "http://127.0.0.1/fpm_status.php?json">
    Instance "fpm-1"
```

Change the "1" to a unique ID for each instance. This can be alphanumeric if you wish, e.g.

```ApacheConf
<url "http://127.0.0.1:8080/fpm_status.php?json">
    Instance "fpm-frontend"
```

