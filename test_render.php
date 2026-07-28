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
    $subjects = $svc->getAllSubjects($req->all());
    $mySubjects = $svc->getUserSubjects($user);
    $view = view('subjects.index', compact('subjects', 'mySubjects'))->render();
    echo 'OK';
} catch (\Exception $e) {
    echo $e->getMessage() . "\n" . $e->getFile() . ':' . $e->getLine();
}
