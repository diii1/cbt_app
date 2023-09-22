<?php

/**
 * Laravel - A PHP Framework for web Artisan
 *
 * @package Laravel
 * @author Admin@CBT.mts
 */

$url = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

if ($url !== '/' && file_exists(__DIR__.'/public'.$url)){
    return false;
}

require_once __DIR__.'/public/index.php';
