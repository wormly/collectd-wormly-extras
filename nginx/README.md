Monitoring nginx with collectd
============

[Follow this guide](https://www.wormly.com/help/collectd-plugins/nginx-monitoring) to fetch Nginx performance metrics with collectd for monitoring, graphing and alerting purposes.

[![Nginx Connection States Monitoring](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/nginx-connection-states.png)](https://www.wormly.com/help/collectd-plugins/nginx-monitoring)
[![Nginx Request Monitoring](https://d1v0bax3d3bxs8.cloudfront.net/cloud-monitoring/nginx-connection-requests.png)](https://www.wormly.com/help/collectd-plugins/nginx-monitoring)

We use the `nginx` collectd plugin to fetch these metrics from a running nginx instance.  This plugin is included by default in our collectd distribution so no additional installation is required.

Note that your nginx binary must be compiled with support for the `stub_status` module to be able to use this feature.  Most distributions include this by default.  You can verify yours by running the following command:

```
nginx -V 2>&1 | grep -q with-http_stub_status_module && echo ALL GOOD
 ```

 All good?  OK, now you need to configure your nginx instance (or instances) to expose its status report over HTTP, so add this snippet to your nginx config file / directory:

```nginx
location /nginx_status {
	stub_status on;
	access_log off;
	allow 127.0.0.1;
	deny all;
}
```

Then you just need to place `monitor-nginx.conf` in your collectd additional config directory; for example `/opt/wormly/collectd/etc/collectd.d/`. You will probably need to edit this file to specify the HTTP listening port of your nginx instance.

Test your collectd configuration:

```Shell
collectd-wormly -T
```

No errors and `{"ok":true}` shown on the console?  Reload the daemon to commence data collection:

```Shell
service collectd-wormly restart
```
