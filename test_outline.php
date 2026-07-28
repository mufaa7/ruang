<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$m = App\Models\Makalah::latest()->first();
echo 'Makalah: ' . $m->judul . "\n";
foreach($m->chapters as $c) {
    echo '- ' . $c->bab_label . ': ' . $c->title . "\n";
    foreach($c->subchapters as $s) {
        echo '  * ' . $s->title . ' (Length: ' . strlen($s->content) . ')' . "\n";
    }
}
