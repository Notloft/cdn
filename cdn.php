<?php
// cdn.php
while (ob_get_level()) {
    ob_end_flush();
}

ini_set('output_buffering', 'off');
ini_set('zlib.output_compression', 'off');
ini_set('implicit_flush', '1');
set_time_limit(0);
ignore_user_abort(true);

ob_implicit_flush(true);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$filename = basename($uri);

$filePath = __DIR__ . '/cdn/' . $filename;
file_put_contents(
    __DIR__ . '/requests.log',
    $filePath,
    FILE_APPEND
);
if (!is_file($filePath)) {
    http_response_code(404);
    exit;
}

$fileSize = filesize($filePath);
$lastModified = gmdate('D, d M Y H:i:s', filemtime($filePath)) . ' GMT';
$etag = '"' . md5_file($filePath) . '"';

header('Content-Type: binary/octet-stream');
header('Accept-Ranges: bytes');
header('Last-Modified: ' . $lastModified);
header('ETag: ' . $etag);
header('Connection: keep-alive');

$range = $_SERVER['HTTP_RANGE'] ?? null;

$start = 0;
$end   = $fileSize - 1;

if ($range && preg_match('/bytes=(\d+)-(\d*)/', $range, $m)) {
    $start = (int)$m[1];
    if ($m[2] !== '') {
        $end = (int)$m[2];
    }

    if ($start > $end || $start >= $fileSize) {
        http_response_code(416);
        header("Content-Range: bytes */$fileSize");
        exit;
    }

    http_response_code(206);
    header("Content-Range: bytes $start-$end/$fileSize");
    header('Content-Length: ' . ($end - $start + 1));
} else {
    http_response_code(200);
    header('Content-Length: ' . $fileSize);
}

$fp = fopen($filePath, 'rb');
fseek($fp, $start);

$chunkSize = 4 * 1024 * 1024; // 4 MB
$bytesLeft = $end - $start + 1;

while ($bytesLeft > 0 && !feof($fp)) {
    $read = min($chunkSize, $bytesLeft);
    $data = fread($fp, $read);
    if ($data === false) break;

    echo $data;
    flush();
    $bytesLeft -= strlen($data);
}

fclose($fp);
exit;
