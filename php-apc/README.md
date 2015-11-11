Monitoring PHP-APC
==========

Follow this guide to fetch PHP-APC performance metrics for use with collectd & Wormly Metrics. You can [find the canonical version of this document here](https://www.wormly.com/help/collectd-plugins/php-apc-monitoring).

[![PHP-APC Monitoring Graph](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/php-apc-monitoring-cache-hit-graph.png)](https://www.wormly.com/help/collectd-plugins/php-apc-monitoring)

We utilize the `curl_json` collectd plugin to perform data collection from PHP-APC, so ensure that your collectd installation has this plugin available:

```Shell
yum install collectd-wormly-curl-json
apt-get install collectd-wormly-curl-json
```

Then, ensure that `apc_metrics.php` is accessible to localhost; e.g. via this URL: `http://127.0.0.1/apc_metrics.php`. You can adjust the `<url>` configuration setting in `monitor-php-apc.conf` accordingly if `apc_metrics.php` is served via a different URL.

Now, place `monitor-php-apc.conf` in your collectd additional config directory; for example `/opt/wormly/collectd/etc/collectd.d/`.

Test your configuration:

```Shell
# Check that the APC query script returns JSON and no errors:
curl http://127.0.0.1/apc_metrics.php

# Then verify collectd config:
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```

Multiple Instances of PHP-APC 
=========

You may have multiple instances of PHP-APC running on a single host (e.g. if you have multiple PHP-FPM pools). Accordingly, you will wish to identify metrics for each instance separately. To achieve this, simply duplicate the `<url>` block and change the URL and Instance values to suit your configuration:

```ApacheConf
<url "http://127.0.0.1/apc_metrics.php">
    Instance "apc-1"
```

Change the "1" to a unique ID for each instance. This can be alphanumeric if you wish, e.g.

```ApacheConf
<url "http://127.0.0.1:8080/apc_metrics.php">
    Instance "apc-webserver-pool"
```

