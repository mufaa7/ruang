<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\FlashcardSet;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class QuizLatihanController extends Controller
{
    public function show(Quiz $quiz): View
    {
        $quiz->load('questions');
        $user = auth()->user();
        
        $result = session('result');
        if (!$result && $user) {
            $attempt = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->first();
            if ($attempt) {
                $result = [
                    'score' => $attempt->score,
                    'correct' => $attempt->correct_answers,
                    'total' => $attempt->total_questions,
                    'submitted_answers' => $attempt->submitted_answers,
                ];
            }
        }
        $backUrl = route('dashboard');
        if ($quiz->subject_id) {
            $backUrl = route('subjects.show', $quiz->subject_id);
        }

        return view('shared.quiz', compact('quiz', 'result', 'backUrl'));
    }

    public function submit(Request $request, Quiz $quiz): RedirectResponse
    {
        $quiz->load('questions');
        $answers = $request->input('answers', []);
        
        $correct = 0;
        $total = 0;
        
        foreach ($quiz->questions as $question) {
            if ($question->type === 'multiple_choice') {
                $total++;
                $submitted = $answers[$question->id] ?? null;
                if ($submitted !== null) {
                    if ($submitted == $question->correct_answer) {
                        $correct++;
                    } elseif (is_array($question->options) && isset($question->options[$submitted]) && $question->options[$submitted] === $question->correct_answer) {
                        $correct++;
                    }
                }
            }
        }
        
        $score = $total > 0 ? round(($correct / $total) * 100) : 0;
        
        $result = [
            'score' => $score,
            'correct' => $correct,
            'total' => $total,
            'submitted_answers' => $answers,
        ];
        
        if (auth()->check()) {
            QuizAttempt::updateOrCreate(
                ['quiz_id' => $quiz->id, 'user_id' => auth()->id()],
                [
                    'score' => $score,
                    'correct_answers' => $correct,
                    'total_questions' => $total,
                    'submitted_answers' => $answers,
                ]
            );
        }
        
        return redirect()->route('latihan.quiz.show', $quiz->id)
            ->with('result', $result)
            ->with('success', 'Kuis selesai dikerjakan!');
    }
}
