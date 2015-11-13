Postfix Monitoring with collectd
==========

[Follow this guide](https://www.wormly.com/help/collectd-plugins/postfix-monitoring) to fetch Postfix performance metrics with collectd for monitoring, graphing and alerting purposes.

[![Postfix Monitoring](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/postfix-latency.png)](https://www.wormly.com/help/collectd-plugins/postfix-monitoring)
[![Postfix Monitoring](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/postfix-message-status.png)](https://www.wormly.com/help/collectd-plugins/postfix-monitoring)

We use the `tail` collectd plugin to fetch these metrics from the Postfix logfile.  This plugin is included by default in our collectd distribution so no additional installation is required.

Simply place `monitor-postfix.conf` in your collectd additional config directory; for example `/opt/wormly/collectd/etc/collectd.d/`. You may need to edit this file to specify the location of your maillog if it is not the standard `/var/log/maillog`.

Test your collectd configuration:

```Shell
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```

Monitoring Multiple Postfix Instances
=========

If you have more than one instance of Postfix that you wish to monitor, simply duplicate the `monitor-postfix.conf` config file and change the `Instance` configuration key accordingly:

```ApacheConf
  <File "/var/log/maillog">
    Instance "postfix"
```

Your second Postfix instance might look like this:

```ApacheConf
  <File "/var/log/second-maillog">
    Instance "postfixSecondInstance"
```

Through the Wormly interface you will see separate metrics and graphs labeled **Postfix** and **Postfix SecondInstance** respectively.

