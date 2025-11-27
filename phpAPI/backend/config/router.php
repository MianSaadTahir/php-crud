<?php
/**
 * Simple Router Helper
 * Extracts path segments from request URI
 */

function getPathSegments() {
    $request_uri = $_SERVER['REQUEST_URI'];
    $script_name = $_SERVER['SCRIPT_NAME'];
    
    // Remove query string
    $path = parse_url($request_uri, PHP_URL_PATH);
    
    // Remove base path if running in subdirectory
    $base_path = dirname(dirname(dirname($script_name)));
    if ($base_path !== '/') {
        $path = str_replace($base_path, '', $path);
    }
    
    // Split path into segments
    $segments = explode('/', trim($path, '/'));
    
    return array_filter($segments, function($segment) {
        return !empty($segment);
    });
}

function getRequestMethod() {
    return $_SERVER['REQUEST_METHOD'];
}

