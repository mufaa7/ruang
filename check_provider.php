<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Setting;

$duckProvider = Setting::where('key','ai_duck_provider')->value('value');
$duckUseDefault = Setting::where('key','ai_duck_use_default')->value('value');
$defaultProvider = Setting::where('key','active_ai_provider')->value('value');

echo "Duck use_default: " . ($duckUseDefault ?? 'not set') . PHP_EOL;
echo "Duck specific provider: " . ($duckProvider ?? 'not set') . PHP_EOL;
echo "Default global provider: " . ($defaultProvider ?? 'not set') . PHP_EOL;
