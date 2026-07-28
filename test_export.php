<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$makalah = App\Models\Makalah::first();
if ($makalah) {
    echo "Judul: " . $makalah->judul . "\n";
    echo "Nama Dosen: " . ($makalah->nama_dosen ?? 'NULL/EMPTY') . "\n";
    echo "Mata Kuliah: " . ($makalah->mata_kuliah ?? 'NULL/EMPTY') . "\n";
    echo "Logo path: " . ($makalah->logo_path ?? 'NULL/EMPTY') . "\n";
    echo "NIM: " . ($makalah->nim ?? 'NULL/EMPTY') . "\n";
} else {
    echo "No Makalah found";
}
