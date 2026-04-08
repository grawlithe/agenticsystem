<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
try {
    echo class_exists(Illuminate\Support\Facades\Ai::class) ? 'Ai exists. ' : 'No Ai. ';
    // Test if Ai class provides embeddings
    $reflection = new ReflectionClass(Illuminate\Support\Facades\Ai::class);
    echo $reflection->hasMethod('embeddings') ? 'Has embeddings method.' : 'No embeddings method.';
} catch(\Exception $e) {
    echo $e->getMessage();
}
