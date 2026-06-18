<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::capture();
$request->server->set('REQUEST_URI', '/admin/login');
$request->server->set('SCRIPT_NAME', '/index.php');

$response = $kernel->handle($request);
echo 'Status: ' . $response->getStatusCode() . PHP_EOL;
echo 'Content length: ' . strlen($response->getContent()) . PHP_EOL;
if ($response->getStatusCode() === 200) {
    echo 'Admin login page loads OK' . PHP_EOL;
} elseif ($response->getStatusCode() === 500) {
    echo 'ERROR 500!' . PHP_EOL;
    // Find exception in response
    if (preg_match('/<h1>([^<]+)<\/h1>/', $response->getContent(), $m)) {
        echo 'Title: ' . $m[1] . PHP_EOL;
    }
}
