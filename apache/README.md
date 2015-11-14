Monitoring Apache
==========

Follow this guide to fetch performance data &amp; the "Apache Scoreboard" for use with collectd & Wormly Metrics. You can [find the canonical version of this document here](https://www.wormly.com/help/collectd-plugins/apache-monitoring).

[![Apache Scoreboard Monitoring Graph](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/apache-scoreboard-graph-2.png)](https://www.wormly.com/help/collectd-plugins/apache-monitoring)

[![Apache Request Rate Graph](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/apache-requests.png)](https://www.wormly.com/help/collectd-plugins/apache-monitoring)

First, enable <em>extended status</em> within your Apache configuration:


```ApacheConf
ExtendedStatus on
<Location /mod_status>
    SetHandler server-status
    Order deny,allow
    Deny from all
    Allow from 127.0.0.1
</Location>
```

Reload or restart Apache to put the updated config into effect:

```Shell
apachectl graceful
```

Now, configure the `apache` plugin for collectd by placing `monitor-apache.conf` in your collectd config directory (e.g. `/opt/wormly/collectd/etc/collectd.d/`):

```ApacheConf
LoadPlugin "apache"
<Plugin "apache">
    # Leave "Instance" blank unless you have multiple Apache instances, in
    # which case you can provide a name to identify each instance
   <Instance "">
       URL "http://127.0.0.1/mod_status?auto"
   </Instance>
</Plugin>
```

Test your configuration:

```Shell
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```




Monitoring Multiple Instances of Apache
=========

You may have multiple instances of Apache running on a single host that you wish to monitor separately. To achieve this, simply duplicate the `<Instance>` block and change the URL and Instance values to suit your configuration:

```ApacheConf
# The default instance
<Instance "">
   URL "http://127.0.0.1/mod_status?auto"
```

For another Apache instance:

```ApacheConf
# The default instance
<Instance "backend-http">
   URL "http://127.0.0.1:8080/mod_status?auto"
```

