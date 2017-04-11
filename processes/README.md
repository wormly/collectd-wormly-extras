Monitor Process state with collectd
==========

[Follow this guide](https://www.wormly.com/help/collectd-plugins/process-monitoring) to fetch process state metrics with collectd for monitoring, graphing and alerting purposes.

[![System Process State Monitoring](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/monitor-processes-collectd.png)](https://www.wormly.com/help/collectd-plugins/process-monitoring)

We use the `processes` collectd plugin to fetch these metrics, and this plugin is included by default in our collectd distribution.

Simply place `monitor-processes.conf` in your collectd additional config directory; for example `/opt/wormly/collectd/etc/collectd.d/`. You will want to edit this file to specify the various system and user processes you are interested in monitoring.

Test your collectd configuration:

```Shell
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```

