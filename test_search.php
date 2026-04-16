<?php

use App\Models\Product;
use App\Services\SemanticSearchService;
use Illuminate\Contracts\Console\Kernel;

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
$service = app(SemanticSearchService::class);
$r = Product::query()->select('*')->selectVectorDistance('embedding', 'test')->first();
$r->makeHidden(['embedding']);
echo 'Keys: '.implode(', ', array_keys($r->toArray())).PHP_EOL;
echo 'JSON Bytes: '.strlen(json_encode([$r])).PHP_EOL;
