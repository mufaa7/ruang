<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$flashcardSet = App\Models\FlashcardSet::where('title', 'Hafalan Istilah')->first();

if ($flashcardSet) {
    // Hapus kuis lama jika ada agar tidak double
    $oldQuiz = App\Models\Quiz::where('title', 'Kuis Evaluasi: ' . $flashcardSet->title)->first();
    if ($oldQuiz) {
        $oldQuiz->questions()->delete();
        $oldQuiz->delete();
    }

    // Buat kuis baru khusus untuk flashcard ini
    $quiz = App\Models\Quiz::create([
        'subject_id' => $flashcardSet->subject_id,
        'title' => 'Kuis Evaluasi: ' . $flashcardSet->title,
        'type' => 'admin',
        'time_limit_minutes' => 10,
    ]);

    // Tambahkan beberapa soal contoh untuk kuis ini
    App\Models\QuizQuestion::create([
        'quiz_id' => $quiz->id,
        'question' => 'Apa itu Algoritma berdasarkan flashcard yang kamu baca?',
        'type' => 'multiple_choice',
        'options' => [
            'A' => 'Langkah-langkah logis menyelesaikan masalah',
            'B' => 'Bahasa pemrograman tingkat tinggi',
            'C' => 'Komponen perangkat keras komputer',
            'D' => 'Nama makanan tradisional'
        ],
        'correct_answer' => 'A',
        'explanation' => 'Algoritma adalah serangkaian langkah logis yang disusun secara sistematis untuk memecahkan suatu masalah.'
    ]);
    
    App\Models\QuizQuestion::create([
        'quiz_id' => $quiz->id,
        'question' => 'Struktur data yang menggunakan prinsip LIFO (Last In First Out) adalah?',
        'type' => 'multiple_choice',
        'options' => [
            'A' => 'Queue',
            'B' => 'Linked List',
            'C' => 'Stack',
            'D' => 'Array'
        ],
        'correct_answer' => 'C',
        'explanation' => 'Stack atau tumpukan menggunakan prinsip LIFO, di mana data terakhir yang masuk adalah yang pertama kali keluar.'
    ]);

    // Sambungkan flashcard ke kuis baru ini
    $flashcardSet->update(['quiz_id' => $quiz->id]);

    echo "Berhasil membuat kuis baru dan menyambungkannya ke flashcard!\n";
} else {
    echo "Flashcard tidak ditemukan.\n";
}
