TCP Connection state with collectd
==========

[Follow this guide](https://www.wormly.com/help/collectd-plugins/tcp-connection-state-monitoring) to fetch TCP Connection State metrics with collectd for monitoring, graphing and alerting purposes.

[![TCP Connection State Monitoring](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/tcp-connection-state-graph2.png)](https://www.wormly.com/help/collectd-plugins/tcp-connection-state-monitoring)

We use the `tcpconns` collectd plugin to fetch these metrics, and this plugin is included by default in our collectd distribution.

Simply place `monitor-tcp-connections.conf` in your collectd additional config directory; for example `/opt/wormly/collectd/etc/collectd.d/`. You may wish to edit this file to specify the remote and local TCP ports you are interested in.

Test your collectd configuration:

```Shell
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```

