<?php
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

file_put_contents(
    __DIR__ . '/requests.log',
    date('c') . ' ' . $_SERVER['REQUEST_METHOD'] . ' ' . $uri . PHP_EOL,
    FILE_APPEND
);

// Check if URL starts with /contents/
if (strpos($uri, '/partners/androidmarket/d.cdn.php') === 0) {
    require __DIR__ . '/cdn-redirect.php';
    exit;
}
if (strpos($uri, '/cdn/') === 0) {
    require __DIR__ . '/cdn.php';
    exit;
}

http_response_code(404);
echo "404";