<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $req = new Illuminate\Http\Request();
    $svc = app(App\Services\SubjectService::class);
    $user = App\Models\User::where('name', 'like', '%Rizky%')->first();
    auth()->login($user);
    $subject = $svc->getUserSubjects($user)->first();
    if(!$subject) { echo 'NO SUBJECTS'; exit; }
    $view = view('subjects.show', compact('subject'))->render();
    echo 'OK';
} catch (\Exception $e) {
    echo $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine();
}
