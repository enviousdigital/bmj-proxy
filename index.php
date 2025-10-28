<?php
// Simple BMJ feed proxy for Render
header('Content-Type: application/rss+xml; charset=utf-8');

$feeds = [
    'jnnp'      => 'https://jnnp.bmj.com/rss/ahead.xml',
    'bmj'       => 'https://www.bmj.com/rss/bmj_latest.xml',
    'bmjopen'   => 'https://bmjopen.bmj.com/rss/current.xml',
    'medicine'  => 'https://bmjmedicine.bmj.com/rss/current.xml',
    'gut'       => 'https://gut.bmj.com/rss/ahead.xml',
    'sports'    => 'https://bjsm.bmj.com/rss/ahead.xml'
];

$feed_key = isset($_GET['feed']) ? strtolower(trim($_GET['feed'])) : '';
if (!$feed_key || !isset($feeds[$feed_key])) {
    http_response_code(400);
    echo "Invalid or missing ?feed= parameter.";
    exit;
}

$url = $feeds[$feed_key];

// Fetch feed
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
    CURLOPT_HTTPHEADER => ['Accept: application/rss+xml,application/xml;q=0.9,*/*;q=0.8'],
    CURLOPT_TIMEOUT => 20,
]);
$data = curl_exec($ch);
$err  = curl_error($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($code == 200 && $data && (stripos($data, '<rss') !== false || stripos($data, '<feed') !== false)) {
    echo $data;
} else {
    echo "Failed to fetch feed: HTTP $code | $err";
}