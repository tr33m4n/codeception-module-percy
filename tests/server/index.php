<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($uri !== '/' && file_exists(__DIR__ . $uri . '.html')) {
    echo file_get_contents(__DIR__ . $uri . '.html');
}
