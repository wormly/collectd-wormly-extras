<?php
/*
Credit goes mostly to Peter Molnar for this script:
https://petermolnar.eu/lightweight-system-monitoring-with-collectd-and-jarmon/
LICENSE: GPL
*/
if (!function_exists('apc_cache_info') OR !function_exists('json_encode')) {
    die("Requires both APC and JSON PHP extensions to be enabled.\n");
}

$MAX_FRAGMENT_SIZE = 5 * 1024 * 1024;

header('Content-type: text/json');
header("Expires: " . gmdate('D, d M Y H:i:s GMT'));

$json = array();
$cache_sys = apc_cache_info('', true);
$cache_user = apc_cache_info('user', true);
$mem = apc_sma_info(true);
$mem_detail = apc_sma_info();

$json['cache_sys'] = $cache_sys;
$json['cache_user'] = $cache_user;

$num_seg = $mem['num_seg'];
$seg_size = $mem['seg_size'];
$avail_mem = $mem['avail_mem'];
$total_mem = $num_seg * $seg_size;
$mem['used_mem'] = $total_mem - $avail_mem;
$util_ratio = (float) $avail_mem / $total_mem;
$mem['utilization_percent'] = (1 - $util_ratio) * 100;

// Fragmentation: 1 - (Largest Block of Free Memory / Total Free Memory)
$total_num_frag = 0;
$total_frag = 0;
$total_free = 0;
$total_free_small = 0;
for($i = 0; $i < $num_seg; $i++) {
    $seg_free_max = 0; $seg_free_total = 0; $seg_num_frag = 0;
    $seg_free_small = 0;
    foreach($mem_detail['block_lists'][$i] as $block) {
        $seg_num_frag += 1;
        if ($block['size'] > $seg_free_max) {
            $seg_free_max = $block['size'];
        }
        if ($block['size'] < $MAX_FRAGMENT_SIZE) {
            $seg_free_small += $block['size'];
        }
        $seg_free_total += $block['size'];
    }
    if ($seg_num_frag > 1) {
        $total_num_frag += $seg_num_frag - 1;
        $total_frag += $seg_free_total - $seg_free_max;
        $total_free_small += $seg_free_small;
    }
    $total_free += $seg_free_total;
}
$frag_count = $total_num_frag;
$frag_avg_size = ($frag_count > 0) ? (float )$total_frag / $frag_count: 0;
$frag_ratio = ($total_free > 0) ? (float) $total_free_small / $total_free : 0;
$mem['fragmentation_percent'] = $frag_ratio * 100;
$mem['fragment_count'] = $frag_count;
$mem['fragment_avg_size'] = $frag_avg_size;

$json['memory'] = $mem;

print(json_encode($json));
