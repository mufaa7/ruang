<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$sets = App\Models\FlashcardSet::whereNull('quiz_id')->get();
foreach($sets as $set) {
    $quiz = App\Models\Quiz::where('title', 'like', 'Uji Nyali: ' . $set->title)->first();
    if($quiz) {
        $set->update(['quiz_id' => $quiz->id]);
        echo 'Fixed: ' . $set->title . "\n";
    }
}
echo "Done";
