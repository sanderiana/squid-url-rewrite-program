#!/usr/bin/env php
<?php

// config
$config = require(__DIR__ . "/config.php");
$conf_timeout = $config["time_out"] ?? 86400;
$conf_redirect = $config["redirect"] ?? [];
$redirect_map = create_redirect_map($conf_redirect);

// stdin
stream_set_timeout(STDIN, $conf_timeout);
while ( $input = fgets(STDIN) ) {
    // parse input
    $parsed = parse_input($input);
    if($parsed == null){continue;}

    // get redirect url
    $url = get_redirect_map($redirect_map,$parsed['domain'],$parsed['port']);

    // output redirect url
    $output = create_response($url, $parsed['url']);
    echo $output;
}

/**
 * @param $map
 * @param $domain
 * @param $port
 * @return bool
 */
function get_redirect_map($map, $domain, $port){
    $port = $port ?? '*';
    return $map[$domain][$port]
        ?? $map[$domain]['*']
        ?? false;
}

/**
 * @param $map
 * @param $domain
 * @param $port
 * @param $url
 */
function set_redirect_map(&$map, $domain, $port, $url){
    $port = $port ?? '*';
    $map[$domain][$port] = $url;
}

/**
 * @param array $config_map
 * @return array
 */
function create_redirect_map($config_map){
    $ret = [];
    foreach((array)$config_map as $map){
        $port = $map['port'] ?? null;
        set_redirect_map($ret, $map['domain'], $port, $map['redirect']);
    }
    return $ret;
}

/**
 * @param $redirect_url
 * @param $input_url
 * @return string
 */
function create_response($redirect_url, $input_url){
    $ret = $redirect_url
        ? 'OK status=302 url="' . $redirect_url . '"' . "\n"
        : $input_url . "\n";
     return $ret;
}

/**
 * parse input value
 * @param $input
 * @return array
 */
function parse_input($input){
    # input parts
    $input_parts = explode(" ", $input);
    $url = $input_parts[0] ?? "";

    # parts
    $scheme = "((?<scheme>http)://)";
    $domain = "(?<domain>[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+){1,})";
    $port = "(:(?<port>[0-9]{1,5}))";
    $query = "(?<query>/.*)?";

    # ssh, http
    $ssl_pattern = "#\A{$domain}{$port}?\Z#u";
    $http_pattern = "#\A{$scheme}{$domain}{$port}?{$query}\Z#u"; #{$domain}{$query}?

    # pattern
    $ret = [];
    if(preg_match($ssl_pattern, $url, $match)){
        $ret['domain'] = $match['domain'] ?? "";
        $ret['port'] = $match['port'] ?? "";
        $ret['url'] = $url;
    }
    elseif(preg_match($http_pattern, $url, $match)){
        $ret['domain'] = $match['domain'] ?? "";
        $ret['port'] = $match['port'] ?? "80";
        $ret['url'] = $url;
    }
    return $ret;
}