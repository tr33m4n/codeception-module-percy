<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$filePath = __DIR__ . $uri . '.html';

if ($uri !== '/' && file_exists($filePath)) {
    echo file_get_contents($filePath);
    die();
}

http_response_code(404);
