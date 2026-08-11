<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$quiz = App\Models\Quiz::where('title', 'Kuis 1 - Dasar Algoritma')->first();
if ($quiz) {
    App\Models\FlashcardSet::where('title', 'Hafalan Istilah')->update(['quiz_id' => $quiz->id]);
    echo "Done linking!\n";
} else {
    echo "Quiz not found\n";
}
