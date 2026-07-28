<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$m = App\Models\Makalah::latest()->first();
$s = $m->chapters->first()->subchapters->first();
if ($s) {
    echo $s->content;
} else {
    echo "No content found for this makalah.";
}
