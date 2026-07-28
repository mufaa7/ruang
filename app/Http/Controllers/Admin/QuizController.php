<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\Subject;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::where('type', 'admin')
            ->doesntHave('flashcardSet')
            ->with(['subject', 'material.user'])
            ->withCount('targets')
            ->latest()
            ->paginate(20);
        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create(Request $request)
    {
        $subjects = Subject::with(['users', 'materials.user'])->orderBy('name')->get();
        $prefillMaterial = null;
        if ($request->has('material_id')) {
            $prefillMaterial = \App\Models\Material::find($request->query('material_id'));
        }
        return view('admin.quizzes.create', compact('subjects', 'prefillMaterial'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'material_id' => 'nullable|exists:materials,id',
            'target_users' => 'nullable|array',
            'target_users.*' => 'exists:users,id',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'questions' => 'nullable|array',
            'questions.*.type' => 'nullable|in:multiple_choice,essay',
            'questions.*.question' => 'nullable|string',
            'questions.*.options' => 'nullable|array|size:4',
            'questions.*.options.*' => 'nullable|string',
            'questions.*.correct_answer' => 'nullable|integer|min:0|max:3',
            'questions.*.explanation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $quiz = Quiz::create([
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'],
                'material_id' => $validated['material_id'] ?? null,
                'time_limit_minutes' => $validated['time_limit_minutes'] ?? 0,
                'type' => 'admin',
            ]);
            
            if (!empty($validated['target_users'])) {
                $quiz->targets()->sync($validated['target_users']);
            }

            foreach ($validated['questions'] as $q) {
                if (empty($q['question'])) continue;
                $type = $q['type'] ?? 'multiple_choice';
                $optionsDict = null;
                $correctLetter = null;

                if ($type === 'multiple_choice') {
                    $optionsMap = ['A', 'B', 'C', 'D'];
                    $correctLetter = $optionsMap[$q['correct_answer']] ?? 'A';
                    
                    $optionsDict = [
                        'A' => $q['options'][0] ?? '',
                        'B' => $q['options'][1] ?? '',
                        'C' => $q['options'][2] ?? '',
                        'D' => $q['options'][3] ?? '',
                    ];
                }

                QuizQuestion::create([
                    'quiz_id' => $quiz->id,
                    'type' => $type,
                    'question' => $q['question'],
                    'options' => $optionsDict,
                    'correct_answer' => $correctLetter,
                    'explanation' => $q['explanation'],
                ]);
            }
        });

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz created successfully.');
    }

    public function edit(Quiz $quiz)
    {
        $subjects = Subject::with(['users', 'materials.user'])->orderBy('name')->get();
        $quiz->load(['questions', 'targets']);
        return view('admin.quizzes.edit', compact('quiz', 'subjects'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'material_id' => 'nullable|exists:materials,id',
            'target_users' => 'nullable|array',
            'target_users.*' => 'exists:users,id',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'questions' => 'nullable|array',
            'questions.*.id' => 'nullable|exists:quiz_questions,id',
            'questions.*.type' => 'nullable|in:multiple_choice,essay',
            'questions.*.question' => 'nullable|string',
            'questions.*.options' => 'nullable|array|size:4',
            'questions.*.options.*' => 'nullable|string',
            'questions.*.correct_answer' => 'nullable|integer|min:0|max:3',
            'questions.*.explanation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $quiz) {
            $quiz->update([
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'],
                'material_id' => $validated['material_id'] ?? null,
                'time_limit_minutes' => $validated['time_limit_minutes'] ?? 0,
            ]);
            
            if (isset($validated['target_users'])) {
                $quiz->targets()->sync($validated['target_users']);
            } else {
                $quiz->targets()->sync([]);
            }

            // Track existing question IDs to delete ones that were removed
            $existingQuestionIds = $quiz->questions()->pluck('id')->toArray();
            $keptQuestionIds = [];

            foreach ($validated['questions'] as $q) {
                if (empty($q['question'])) continue;
                $type = $q['type'] ?? 'multiple_choice';
                $optionsDict = null;
                $correctLetter = null;

                if ($type === 'multiple_choice') {
                    $optionsMap = ['A', 'B', 'C', 'D'];
                    $correctLetter = $optionsMap[$q['correct_answer']] ?? 'A';
                    
                    $optionsDict = [
                        'A' => $q['options'][0] ?? '',
                        'B' => $q['options'][1] ?? '',
                        'C' => $q['options'][2] ?? '',
                        'D' => $q['options'][3] ?? '',
                    ];
                }

                if (!empty($q['id']) && in_array($q['id'], $existingQuestionIds)) {
                    $question = QuizQuestion::find($q['id']);
                    $question->update([
                        'type' => $type,
                        'question' => $q['question'],
                        'options' => $optionsDict,
                        'correct_answer' => $correctLetter,
                        'explanation' => $q['explanation'],
                    ]);
                    $keptQuestionIds[] = $q['id'];
                } else {
                    $newQuestion = QuizQuestion::create([
                        'quiz_id' => $quiz->id,
                        'type' => $type,
                        'question' => $q['question'],
                        'options' => $optionsDict,
                        'correct_answer' => $correctLetter,
                        'explanation' => $q['explanation'],
                    ]);
                    $keptQuestionIds[] = $newQuestion->id;
                }
            }

            // Delete removed questions
            $questionsToDelete = array_diff($existingQuestionIds, $keptQuestionIds);
            if (!empty($questionsToDelete)) {
                QuizQuestion::whereIn('id', $questionsToDelete)->delete();
            }
        });

        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz updated successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->targets()->detach();
        $quiz->questions()->delete();
        $quiz->delete();
        return redirect()->route('admin.quizzes.index')->with('success', 'Quiz deleted successfully.');
    }
}

