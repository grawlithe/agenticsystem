<?php

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Ai;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
try {
    echo class_exists(Ai::class) ? 'Ai exists. ' : 'No Ai. ';
    // Test if Ai class provides embeddings
    $reflection = new ReflectionClass(Ai::class);
    echo $reflection->hasMethod('embeddings') ? 'Has embeddings method.' : 'No embeddings method.';
} catch (Exception $e) {
    echo $e->getMessage();
}
