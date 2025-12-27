<?php
// cdn-redirect.php

$dbFile = __DIR__ . '/products.json';
if (!file_exists($dbFile)) {
    http_response_code(500);
    exit;
}

$db = json_decode(file_get_contents($dbFile), true);
if (!is_array($db)) {
    http_response_code(500);
    exit;
}

$product = $_GET['product'] ?? null;
$version = $_GET['version'] ?? null;
$head    = isset($_GET['head']) ? (int)$_GET['head'] : 0;

if (!$product || !$version) {
    http_response_code(400);
    echo "Please specify product and version in url.";
    exit;
}

if (!isset($db[$product][$version])) {
    http_response_code(404);
    exit;
}

$entry = $db[$product][$version];

if (isset($entry['illegal']) && $entry['illegal'] === true) {
    http_response_code(451);
    header('Content-Type: text/plain; charset=UTF-8');
    echo "Unavailable For Legal Reasons.";
    exit;
}

header('x-gl-generic: ' . ($entry['generic'] ?? 'yes'));
header('x-gl-version: ' . $version);
header('x-gl-size: ' . ($entry['size'] ?? 0));
header('x-gl-release: ' . ($entry['release'] ?? 0));
header('Accept-Ranges: bytes');
header('Content-Type: text/html; charset=UTF-8');

if ($head === 1) {
    http_response_code(200);
    header('Content-Length: 0');
    exit;
}

http_response_code(301);
header('Location: ' . $entry['url']);
header('Content-Length: 0');
exit;
