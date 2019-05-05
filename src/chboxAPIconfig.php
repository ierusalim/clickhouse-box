<?php

    // ClickHouse Server API-URL
    $clickhouse_url = 'http://user:password@localhost:8123/';

    // Who can access API? Array of enabled user IP
    $api_enabled_ip_arr = [
        '127.0.0.1',
        '192.168.1.10',
        // ...
    ];

    // replace this config settins if this file exists
    @include dirname(dirname(__DIR__)) .'/localenv.php';


    // response format ( see function api_return )
    $GLOBALS['api_ret_mode'] = 'http'; // or 'html', 'jsonrpc1', 'jsonrpc2'

    // What methods API can do?

    // For each enabled method set one file, where defined function with methods name
    $apim_s_file = __DIR__ . DIRECTORY_SEPARATOR . 'chboxAPImethodsS.php';
    $apim_chro_file = __DIR__ . DIRECTORY_SEPARATOR . 'chboxAPImethodsCHRO.php';
    $api_methods_enabled = [
        // Simple requests (without connection to ClickHouse Server)
        'req'  => $apim_s_file,

        // ClickHouse Read-Only Requests
        'chver' => $apim_chro_file,
        'getVersion' => $apim_chro_file,
        'getUptime' => $apim_chro_file,
        'getSystemSettings' => $apim_chro_file,
        'getDatabasesList' => $apim_chro_file,
        'getTablesList' => $apim_chro_file,
        'getTableInfo' => $apim_chro_file,
        'getTableFields' => $apim_chro_file,
    ];
