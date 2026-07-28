<?php

namespace App\Http\Controllers;

use App\Services\ActivityService;
use App\Services\NoteService;
use App\Services\PaperService;
use App\Services\SubjectService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PaperService   $paperService,
        private readonly NoteService    $noteService,
        private readonly SubjectService $subjectService,
        private readonly ActivityService $activityService,
    ) {}

    public function index(): View
    {
        $user = auth()->user();

        $recentActivities = $this->activityService->getRecentActivities($user, 8, ['note.created', 'note.updated', 'note.deleted', 'note_created', 'catatan_dibuat']);

        
        $quotes = [
            ['text' => 'We are the champions, my friends. And we\'ll keep on fighting till the end.', 'author' => 'Freddie Mercury'],
            ['text' => 'Life is very short, and there\'s no time for fussing and fighting, my friend.', 'author' => 'John Lennon'],
            ['text' => 'And anytime you feel the pain, hey Jude, refrain, don\'t carry the world upon your shoulders.', 'author' => 'Lennon & McCartney'],
            ['text' => 'Wake up the dawn and ask her why. A dreamer dreams she never dies.', 'author' => 'Noel Gallagher'],
            ['text' => 'Dream on, dream on, dream until your dreams come true.', 'author' => 'Steven Tyler'],
            ['text' => 'There will be an answer, let it be.', 'author' => 'Paul McCartney'],
            ['text' => '\'Cause all of the stars are fading away. Just try not to worry, you\'ll see them some day.', 'author' => 'Noel Gallagher'],
            ['text' => 'I think you\'re the same as me. We see things they\'ll never see. You and I are gonna live forever.', 'author' => 'Noel Gallagher'],
            ['text' => 'I am the resurrection and I am the light.', 'author' => 'Ian Brown & John Squire'],
            ['text' => 'And in the end, the love you take is equal to the love you make.', 'author' => 'Paul McCartney'],
            ['text' => 'We are diamonds. Under this pressure, under this weight, we are diamonds.', 'author' => 'Chris Martin'],
            ['text' => 'Yes, there are two paths you can go by, but in the long run, there\'s still time to change the road you\'re on.', 'author' => 'Page & Plant'],
            ['text' => 'How I wish, how I wish you were here. We\'re just two lost souls swimming in a fish bowl, year after year.', 'author' => 'Roger Waters'],
            ['text' => 'The sun is the same in a relative way, but you\'re older. Shorter of breath, and one day closer to death.', 'author' => 'Roger Waters'],
            ['text' => 'One day, I\'m gonna grow wings, a chemical reaction, hysterical and useless.', 'author' => 'Thom Yorke'],
        ];
        $quote = $quotes[array_rand($quotes)];

        $currentTrack = (object)['title' => 'Song 2', 'artist' => 'Blur'];

        
        $activeDoc = \App\Models\Makalah::where('user_id', $user->id)->latest('updated_at')->first();
        
        $progress = 0;
        $wordCount = 0;
        
        if ($activeDoc) {
            $contents = $activeDoc->contents()->pluck('content')->implode(' ');
            $wordCount = str_word_count(strip_tags($contents));
            
            // Kalkulasi progres sederhana berdasarkan kelengkapan
            $progress += 10; // Makalah ada
            if ($activeDoc->chapters()->count() > 0) $progress += 40;
            if ($activeDoc->references()->count() > 0) $progress += 20;
            if ($wordCount >= 500) $progress += 30;
            elseif ($wordCount > 0) $progress += ($wordCount / 500) * 30;
            
            $progress = (int) min(100, $progress);
        }

        $deadlines = $user->deadlines()->orderBy('due_date')->get();
        
        $activities = $recentActivities->map(function ($activity) {
            $icon = match($activity->type) {
                'paper_created', 'makalah_created', 'makalah_updated', 'paper.published' => 'ph ph-file-text',
                'note.created', 'note_created' => 'ph ph-note-pencil',
                'quiz_completed', 'quiz.generated' => 'ph ph-brain',
                'pomodoro_completed' => 'ph ph-timer',
                'material_added' => 'ph ph-book-open',
                'subject.created' => 'ph ph-books',
                'deadline_created' => 'ph ph-calendar-plus',
                default => 'ph ph-activity',
            };
            return [
                'icon' => $icon,
                'title' => $activity->description,
                'time' => $activity->created_at->diffForHumans(),
            ];
        });

        $greeting = $this->getGreeting($user->name);

        // Kalkulasi Statistik Hari Ini
        $todayNotesCount = \App\Models\Note::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->count();
            
        $todayNotesContent = \App\Models\Note::where('user_id', $user->id)
            ->whereDate('updated_at', today())
            ->pluck('content')
            ->implode(' ');
            
        $todayMakalahContent = \App\Models\MakalahSubchapter::whereHas('chapter.makalah', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->whereDate('updated_at', today())
            ->pluck('content')
            ->implode(' ');

        $todayWords = str_word_count(strip_tags($todayNotesContent . ' ' . $todayMakalahContent));
        $todayNotes = $todayNotesCount;
        
        $pomodoroCount = \App\Models\Activity::where('user_id', $user->id)
            ->where('type', 'pomodoro_completed')
            ->whereDate('created_at', today())
            ->count();
            
        $focusMinutes = $pomodoroCount * 25;

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $agendas = \App\Models\Agenda::where('user_id', $user->id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function($agenda) {
                return \Carbon\Carbon::parse($agenda->date)->day;
            });

        $deadlinesByDay = \App\Models\Deadline::where('user_id', $user->id)
            ->whereBetween('due_date', [$startOfMonth, $endOfMonth])
            ->get()
            ->groupBy(function($deadline) {
                return \Carbon\Carbon::parse($deadline->due_date)->day;
            });

        $footers = [
            'ctrl + s dulu.',
            'save dulu baru panik.',
            'bab II ga bakal nulis sendiri.',
            'playlist bagus. tinggal ngetik.',
            'pelan pelan juga kelar.',
            'revisi emg ga ada abisnya.',
            'semoga dosennya ga banyak revisi.',
            'udah minum belum?',
            'jangan lupa istirahat bentar.',
            'semoga ide dateng sebelum deadline.'
        ];

        return view('dashboard', compact(
            'recentActivities',
            'greeting',
            'agendas',
            'deadlinesByDay',
            'todayWords',
            'focusMinutes',
            'todayNotes',
            'quote',
            'activeDoc',
            'wordCount',
            'progress',
            'deadlines',
            'activities',
            'footers',
            'currentTrack'
        ));
    }

    private function getGreeting(string $name): string
    {
        $hour = (int) now()->format('H');

        if ($hour >= 5 && $hour < 11) {
            $greetings = [
                "pagi, {{name}}. masih ada kesempatan buat jadi mahasiswa teladan.",
                "pagi, {{name}}. semoga dosennya lupa ngasih tugas baru.",
                "pagi, {{name}}. kopi dulu, paniknya nanti.",
                "pagi, {{name}}. semoga hari ini ga ada “sebentar ya kelasnya dipindah.”",
                "pagi, {{name}}. buka RUANG dulu, rebahan lanjut nanti.",
                "pagi, {{name}}. semoga wifi ga nyerah duluan.",
                "pagi, {{name}}. semoga ide muncul sebelum deadline.",
                "pagi, {{name}}. tugasnya masih nungguin tuh.",
                "pagi, {{name}}. semangatnya mana? gapapa dicari pelan-pelan.",
                "pagi, {{name}}. minimal jangan telat lagi."
            ];
        } elseif ($hour >= 11 && $hour < 15) {
            $greetings = [
                "siang, {{name}}. masih sempet pura-pura produktif.",
                "siang, {{name}}. satu paragraf juga progress.",
                "siang, {{name}}. jangan kalah sama rasa mager.",
                "siang, {{name}}. tugasnya ga bakal ngerjain dirinya sendiri.",
                "siang, {{name}}. bentar lagi sore, bentar lagi panik.",
                "siang, {{name}}. gas sebelum ngantuk menang.",
                "siang, {{name}}. scroll bentar boleh, sejam jangan.",
                "siang, {{name}}. dosennya percaya sama kamu. sayangnya deadline juga.",
                "siang, {{name}}. buka satu dokumen ga bikin sakit kok.",
                "siang, {{name}}. tinggal mulai doang."
            ];
        } elseif ($hour >= 15 && $hour < 18) {
            $greetings = [
                "sore, {{name}}. udah capek? sama.",
                "sore, {{name}}. bentar lagi malam, bentar lagi mode kebut.",
                "sore, {{name}}. jangan lupa makan, otak juga butuh bensin.",
                "sore, {{name}}. tugasnya masih sabar nunggu.",
                "sore, {{name}}. hari ini jangan kosong-kosong amat.",
                "sore, {{name}}. satu halaman dulu aja.",
                "sore, {{name}}. gas dikit, nyeselnya belakangan.",
                "sore, {{name}}. jangan sampe besok ngomong “coba kemarin…”",
                "sore, {{name}}. tinggal buka laptop doang kok.",
                "sore, {{name}}. semangatnya emang ilang, tapi deadlinenya engga."
            ];
        } else {
            $greetings = [
                "malam, {{name}}. playlist nyala, tugas belum.",
                "malam, {{name}}. waktunya pura-pura fokus.",
                "malam, {{name}}. jangan sampe jam 2 baru buka file.",
                "malam, {{name}}. besok adalah masalah {{name}} besok.",
                "malam, {{name}}. ayo kita bohong ke diri sendiri, “bentar doang.”",
                "malam, {{name}}. satu lagu, satu paragraf.",
                "malam, {{name}}. semoga yang blank cuma layar, bukan otak.",
                "malam, {{name}}. panik boleh, nyerah jangan.",
                "malam, {{name}}. gas dulu, overthinking nanti.",
                "malam, {{name}}. jangan kalah sama playlist."
            ];
        }

        $greeting = $greetings[array_rand($greetings)];
        
        // Extract the first name
        $firstName = explode(' ', trim($name))[0];
        
        return str_replace('{{name}}', $firstName, $greeting);
    }
}
