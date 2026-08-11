<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PaperController;
use App\Http\Controllers\PaperSectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\AiCopilotController;
use App\Http\Controllers\SpotifyController;
use Illuminate\Support\Facades\Route;

// â”€â”€ Public Routes â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/explore', [PaperController::class, 'index'])->name('papers.explore');
Route::get('/papers/{paper:slug}', [PaperController::class, 'show'])->name('papers.show');

// â”€â”€ Authenticated Routes â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Deadlines & Agendas
    Route::resource('deadlines', \App\Http\Controllers\DeadlineController::class)->only(['store', 'update', 'destroy']);
    Route::resource('agendas', \App\Http\Controllers\AgendaController::class)->only(['store', 'update', 'destroy']);

    // Papers
    Route::prefix('my/papers')->name('papers.')->group(function () {
        Route::get('/', [PaperController::class, 'myPapers'])->name('my');
        Route::get('/create', [PaperController::class, 'create'])->name('create');
        Route::post('/', [PaperController::class, 'store'])->name('store');
        Route::get('/{paper}/edit', [PaperController::class, 'edit'])->name('edit');
        Route::patch('/{paper}', [PaperController::class, 'update'])->name('update');
        Route::post('/{paper}/publish', [PaperController::class, 'publish'])->name('publish');
        Route::delete('/{paper}', [PaperController::class, 'destroy'])->name('destroy');

        // Paper Sections
        Route::post('/{paper}/sections', [PaperSectionController::class, 'store'])->name('sections.store');
        Route::patch('/{paper}/sections/{section}', [PaperSectionController::class, 'update'])->name('sections.update');
        Route::delete('/{paper}/sections/{section}', [PaperSectionController::class, 'destroy'])->name('sections.destroy');
    });

    // Coretan
    Route::prefix('coretan')->name('coretan.')->group(function () {
        Route::get('/', [NoteController::class, 'index'])->name('index');
        Route::post('/', [NoteController::class, 'store'])->name('store');
        Route::get('/{note}/edit', [NoteController::class, 'edit'])->name('edit');
        Route::patch('/{note}', [NoteController::class, 'update'])->name('update');
        Route::post('/{note}/pin', [NoteController::class, 'togglePin'])->name('pin');
        Route::delete('/{note}', [NoteController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/restore', [NoteController::class, 'restore'])->name('restore');
    });

    // Subjects (Belajar)
    Route::prefix('subjects')->name('subjects.')->group(function () {
        Route::get('/', [SubjectController::class, 'index'])->name('index');
        Route::get('/create', [SubjectController::class, 'create'])->name('create');
        Route::post('/', [SubjectController::class, 'store'])->name('store');
        Route::get('/{subject}', [SubjectController::class, 'show'])->name('show');
        Route::get('/{subject}/edit', [SubjectController::class, 'edit'])->name('edit');
        Route::put('/{subject}', [SubjectController::class, 'update'])->name('update');
        Route::delete('/{subject}', [SubjectController::class, 'destroy'])->name('destroy');
        // Route::post('/{subject}/join', [SubjectController::class, 'join'])->name('join');
        // Route::post('/{subject}/leave', [SubjectController::class, 'leave'])->name('leave');
        
        // AI Summarizer Route
        Route::post('/{subject}/notes/ai', [\App\Http\Controllers\NoteController::class, 'generateAi'])->name('notes.ai');
        
        // Materials (Sub-resource)
        Route::post('/{subject}/materials', [\App\Http\Controllers\MaterialController::class, 'store'])->name('materials.store');
        Route::patch('/{subject}/materials/{material}', [\App\Http\Controllers\MaterialController::class, 'update'])->name('materials.update');
        Route::delete('/{subject}/materials/{material}', [\App\Http\Controllers\MaterialController::class, 'destroy'])->name('materials.destroy');
    });

    // Makalah Routes
    Route::resource('makalah', App\Http\Controllers\MakalahController::class);
    Route::post('makalah/{makalah}/chapters', [App\Http\Controllers\MakalahController::class, 'storeChapter'])->name('makalah.chapters.store');
    Route::patch('makalah/{makalah}/chapters/{chapter}', [App\Http\Controllers\MakalahController::class, 'updateChapter'])->name('makalah.chapters.update');
    Route::delete('makalah/{makalah}/chapters/{chapter}', [App\Http\Controllers\MakalahController::class, 'destroyChapter'])->name('makalah.chapters.destroy');

    // Subchapters
    Route::post('makalah/{makalah}/chapters/{chapter}/subchapters', [App\Http\Controllers\MakalahController::class, 'storeSubchapter'])->name('makalah.subchapters.store');
    Route::put('makalah/{makalah}/subchapters/{subchapter}', [App\Http\Controllers\MakalahController::class, 'updateSubchapter'])->name('makalah.subchapters.update');
    Route::delete('makalah/{makalah}/subchapters/{subchapter}', [App\Http\Controllers\MakalahController::class, 'destroySubchapter'])->name('makalah.subchapters.destroy');

    Route::post('/makalah/{makalah}/contents', [App\Http\Controllers\MakalahController::class, 'updateContent'])->name('makalah.contents.update');
    Route::get('/makalah/{makalah}/export/pdf', [App\Http\Controllers\MakalahController::class, 'exportPdf'])->name('makalah.export.pdf');
    Route::get('/makalah/{makalah}/export/word', [App\Http\Controllers\MakalahController::class, 'exportWord'])->name('makalah.export.word');

    // References
    Route::post('/makalah/{makalah}/references', [App\Http\Controllers\MakalahController::class, 'storeReference'])->name('makalah.references.store');
    Route::put('/makalah/{makalah}/references/{reference}', [App\Http\Controllers\MakalahController::class, 'updateReference'])->name('makalah.references.update');
    Route::delete('/makalah/{makalah}/references/{reference}', [App\Http\Controllers\MakalahController::class, 'destroyReference'])->name('makalah.references.destroy');

    Route::get('/dengerin', function() { return view('dengerin.index'); })->name('dengerin.index');
    Route::get('/jejak', [\App\Http\Controllers\JejakController::class, 'index'])->name('jejak.index');
    Route::get('/ide', [\App\Http\Controllers\IdeController::class, 'index'])->name('ide.index');
    Route::post('/ide', [\App\Http\Controllers\IdeController::class, 'store'])->name('ide.store');
    Route::get('/about', function() { return view('about.index'); })->name('about.index');
    
    // Spotify Integration
    Route::prefix('spotify')->name('spotify.')->group(function () {
        Route::get('/login', [SpotifyController::class, 'login'])->name('login');
        Route::get('/callback', [SpotifyController::class, 'callback'])->name('callback');
        Route::get('/status', [SpotifyController::class, 'status'])->name('status');
        Route::put('/play', [SpotifyController::class, 'play'])->name('play');
        Route::put('/pause', [SpotifyController::class, 'pause'])->name('pause');
        Route::post('/next', [SpotifyController::class, 'next'])->name('next');
        Route::post('/prev', [SpotifyController::class, 'prev'])->name('prev');
    });
    
    // AI Copilot
    Route::post('/api/ai/autocomplete', [AiCopilotController::class, 'autocomplete'])->middleware('throttle:30,1')->name('api.ai.autocomplete');
    Route::post('/api/ai/references/{makalah}', [AiCopilotController::class, 'generateReferences'])->middleware('throttle:10,1')->name('api.ai.references');
    Route::post('/api/ai/makalah/{makalah}/generate-full', [AiCopilotController::class, 'generateFullMakalah'])->middleware('throttle:ai-generate')->name('api.ai.generate-full');
    Route::post('/api/ai/makalah/{makalah}/cancel', [AiCopilotController::class, 'cancelGenerate'])->name('api.ai.cancel');
    Route::post('/api/ai/makalah/{makalah}/subchapter/{subchapter}/regenerate', [AiCopilotController::class, 'regenerateSubchapter'])->middleware('throttle:10,1')->name('api.ai.regenerate-subchapter');
    Route::get('/api/ai/makalah/{makalah}/status', [AiCopilotController::class, 'checkAiStatus'])->name('api.ai.status');
    Route::get('/api/ai/makalah/{makalah}/chapters-html', [AiCopilotController::class, 'getChaptersHtml'])->name('api.ai.chapters-html');
});

// â”€â”€ Admin Routes â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
Route::middleware(['auth', 'verified', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/notes', [\App\Http\Controllers\Admin\DashboardController::class, 'saveNotes'])->name('dashboard.notes');
    Route::get('/materials', [\App\Http\Controllers\Admin\MaterialController::class, 'index'])->name('materials.index');
    
    // Summary Requests
    Route::get('/summary-requests', [\App\Http\Controllers\Admin\SummaryRequestController::class, 'index'])->name('summary_requests.index');
    Route::get('/summary-requests/{summaryRequest}/fulfill', [\App\Http\Controllers\Admin\SummaryRequestController::class, 'fulfill'])->name('summary_requests.fulfill');
    Route::post('/summary-requests/{summaryRequest}/fulfill', [\App\Http\Controllers\Admin\SummaryRequestController::class, 'storeNote'])->name('summary_requests.storeNote');

    Route::resource('quizzes', App\Http\Controllers\Admin\QuizController::class);
    Route::resource('flashcards', App\Http\Controllers\Admin\FlashcardSetController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['create', 'edit', 'show']);
    
    // Impersonate Route
    Route::post('/users/{user}/impersonate', [App\Http\Controllers\Admin\UserController::class, 'impersonate'])->name('users.impersonate');

    // AI Monitor
    Route::get('/monitor', [App\Http\Controllers\Admin\AiMonitorController::class, 'index'])->name('monitor.index');
    
    // AI Settings
    Route::get('/ai-settings', [App\Http\Controllers\Admin\AiSettingController::class, 'index'])->name('ai_settings.index');
    Route::post('/ai-settings', [App\Http\Controllers\Admin\AiSettingController::class, 'store'])->name('ai_settings.store');
    Route::post('/ai-settings/test', [App\Http\Controllers\Admin\AiSettingController::class, 'testConnection'])->name('ai_settings.test');
});

// Leave Impersonate Route (Needs to be accessible by standard user)
Route::middleware(['auth'])->post('/impersonate/leave', [App\Http\Controllers\Admin\UserController::class, 'leaveImpersonate'])->name('impersonate.leave');

Route::middleware(['auth'])->group(function () {
    Route::get('/latihan/quiz/{quiz}', [App\Http\Controllers\QuizLatihanController::class, 'show'])->name('latihan.quiz.show');
    Route::post('/latihan/quiz/{quiz}/submit', [App\Http\Controllers\QuizLatihanController::class, 'submit'])->name('latihan.quiz.submit');
    Route::get('/latihan/flashcards/{flashcardSet}', [App\Http\Controllers\FlashcardLatihanController::class, 'show'])->name('latihan.flashcard.show');
    
    // Duck Mascot Routes
    Route::post('/duck/event', [App\Http\Controllers\DuckController::class, 'event'])->name('duck.event');
    Route::post('/duck/chat', [App\Http\Controllers\DuckController::class, 'chat'])->name('duck.chat');
});

require __DIR__ . '/auth.php';
