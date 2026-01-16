<?php
// d.php

$dbFile = __DIR__ . '/bookmarks.json';
if (!file_exists($dbFile)) {
    http_response_code(500);
    exit;
}

$db = json_decode(file_get_contents($dbFile), true);
if (!is_array($db)) {
    http_response_code(500);
    exit;
}

$bookmark = $_GET['d'] ?? null;

if (!$bookmark || !isset($db[$bookmark])) {
    http_response_code(200);
    header("Content-Type: text/plain; charset=UTF-8");
    echo "Error: Your bookmark is not correct. Please check its syntax. (and don't contact gameloft pls)";
    exit;
}

$file = __DIR__ . '/' . $db[$bookmark]['path'];

if (!is_file($file)) {
    http_response_code(200);
    header("Content-Type: text/plain; charset=UTF-8");
    echo "Error: File not available.";
    exit;
}

/* ---- serve file ---- */

http_response_code(200);
header("Content-Type: application/octet-stream");
header("Content-Length: " . filesize($file));
header("Accept-Ranges: bytes");
header("Connection: keep-alive");

readfile($file);
exit;
