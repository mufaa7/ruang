<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FlashcardSet;
use App\Models\Flashcard;
use App\Models\Subject;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashcardSetController extends Controller
{
    public function index()
    {
        $flashcardSets = FlashcardSet::with('subject')->latest()->paginate(20);
        return view('admin.flashcards.index', compact('flashcardSets'));
    }

    public function create(Request $request)
    {
        $subjects = Subject::with('users')->orderBy('name')->get();
        $prefillMaterial = null;
        if ($request->has('material_id')) {
            $prefillMaterial = \App\Models\Material::find($request->query('material_id'));
        }
        return view('admin.flashcards.create', compact('subjects', 'prefillMaterial'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'material_id' => 'nullable|exists:materials,id',
            'flashcards' => 'required|array|min:1',
            'flashcards.*.front' => 'nullable|string',
            'flashcards.*.back' => 'nullable|string',
            'target_users' => 'nullable|array',
            'target_users.*' => 'exists:users,id',
            'enable_quiz' => 'nullable|boolean',
            'quiz_title' => 'nullable|string|max:255',
            'questions' => 'nullable|array',
            'questions.*.type' => 'nullable|in:multiple_choice,essay',
            'questions.*.question' => 'nullable|string',
            'questions.*.options' => 'nullable|array|min:4|max:4',
            'questions.*.correct_answer' => 'nullable|integer|min:0|max:3',
            'questions.*.explanation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $set = FlashcardSet::create([
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'],
                'material_id' => $validated['material_id'] ?? null,
                'type' => 'admin',
                'user_id' => auth()->id(),
            ]);

            if (!empty($validated['target_users'])) {
                $set->targets()->attach($validated['target_users']);
            }

            foreach ($validated['flashcards'] as $card) {
                if (empty($card['front']) || empty($card['back'])) continue;
                Flashcard::create([
                    'flashcard_set_id' => $set->id,
                    'front' => $card['front'],
                    'back' => $card['back'],
                ]);
            }

            if (!empty($validated['questions'])) {
                $quizTitle = !empty($validated['quiz_title']) ? $validated['quiz_title'] : 'Uji Nyali: ' . $validated['title'];
                $quiz = Quiz::create([
                    'title' => $quizTitle,
                    'subject_id' => $validated['subject_id'],
                    'material_id' => $validated['material_id'] ?? null,
                    'time_limit_minutes' => 15,
                    'is_published' => true,
                    'type' => 'admin',
                    'user_id' => auth()->id(),
                ]);

                if (!empty($validated['target_users'])) {
                    $quiz->targets()->attach($validated['target_users']);
                }
                
                $set->update(['quiz_id' => $quiz->id]);

                foreach ($validated['questions'] as $q) {
                    if (empty($q['question'])) continue;
                    QuizQuestion::create([
                        'quiz_id' => $quiz->id,
                        'type' => $q['type'],
                        'question' => $q['question'],
                        'options' => $q['type'] === 'multiple_choice' ? $q['options'] : null,
                        'correct_answer' => $q['type'] === 'multiple_choice' ? (int) $q['correct_answer'] : null,
                        'explanation' => $q['explanation'] ?? null,
                    ]);
                }

                $set->update(['quiz_id' => $quiz->id]);
            }
        });

        return redirect()->route('admin.flashcards.index')->with('success', 'Flashcard set created successfully.');
    }

    public function edit(FlashcardSet $flashcard)
    {
        // Parameter injected as $flashcard because of route binding name convention
        $flashcardSet = $flashcard;
        $subjects = Subject::with('users')->orderBy('name')->get();
        $flashcardSet->load('flashcards', 'targets');
        return view('admin.flashcards.edit', compact('flashcardSet', 'subjects'));
    }

    public function update(Request $request, FlashcardSet $flashcard)
    {
        $flashcardSet = $flashcard;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'flashcards' => 'required|array|min:1',
            'flashcards.*.id' => 'nullable|exists:flashcards,id',
            'flashcards.*.front' => 'nullable|string',
            'flashcards.*.back' => 'nullable|string',
            'target_users' => 'nullable|array',
            'target_users.*' => 'exists:users,id',
            'enable_quiz' => 'nullable|boolean',
            'quiz_title' => 'nullable|string|max:255',
            'questions' => 'nullable|array',
            'questions.*.id' => 'nullable|exists:quiz_questions,id',
            'questions.*.type' => 'nullable|in:multiple_choice,essay',
            'questions.*.question' => 'nullable|string',
            'questions.*.options' => 'nullable|array|min:4|max:4',
            'questions.*.correct_answer' => 'nullable|integer|min:0|max:3',
            'questions.*.explanation' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $flashcardSet) {
            $flashcardSet->update([
                'title' => $validated['title'],
                'subject_id' => $validated['subject_id'],
            ]);

            if (isset($validated['target_users'])) {
                $flashcardSet->targets()->sync($validated['target_users']);
            } else {
                $flashcardSet->targets()->detach();
            }

            $existingIds = $flashcardSet->flashcards()->pluck('id')->toArray();
            $keptIds = [];

            foreach ($validated['flashcards'] as $card) {
                if (empty($card['front']) || empty($card['back'])) {
                    continue;
                }
                if (!empty($card['id']) && in_array($card['id'], $existingIds)) {
                    $existing = Flashcard::find($card['id']);
                    $existing->update([
                        'front' => $card['front'],
                        'back' => $card['back'],
                    ]);
                    $keptIds[] = $card['id'];
                } else {
                    $newCard = Flashcard::create([
                        'flashcard_set_id' => $flashcardSet->id,
                        'front' => $card['front'],
                        'back' => $card['back'],
                    ]);
                    $keptIds[] = $newCard->id;
                }
            }

            $toDelete = array_diff($existingIds, $keptIds);
            if (!empty($toDelete)) {
                Flashcard::whereIn('id', $toDelete)->delete();
            }

            if (!empty($validated['questions'])) {
                $quizTitle = !empty($validated['quiz_title']) ? $validated['quiz_title'] : 'Uji Nyali: ' . $validated['title'];
                
                if ($flashcardSet->quiz_id) {
                    $quiz = $flashcardSet->quiz;
                    $quiz->update([
                        'title' => $quizTitle,
                        'subject_id' => $validated['subject_id']
                    ]);
                    
                    if (isset($validated['target_users'])) {
                        $quiz->targets()->sync($validated['target_users']);
                    } else {
                        $quiz->targets()->detach();
                    }
                } else {
                    $quiz = Quiz::create([
                        'title' => $quizTitle,
                        'subject_id' => $validated['subject_id'],
                        'material_id' => $flashcardSet->material_id ?? null,
                        'time_limit_minutes' => 15,
                        'is_published' => true,
                        'type' => 'admin',
                        'user_id' => auth()->id(),
                    ]);
                    
                    if (!empty($validated['target_users'])) {
                        $quiz->targets()->attach($validated['target_users']);
                    }
                    $flashcardSet->update(['quiz_id' => $quiz->id]);
                }

                if (!empty($validated['questions'])) {
                    $existingQIds = $quiz->questions()->pluck('id')->toArray();
                    $keptQIds = [];

                    foreach ($validated['questions'] as $q) {
                        if (empty($q['question'])) {
                            continue;
                        }
                        if (!empty($q['id']) && in_array($q['id'], $existingQIds)) {
                            $existingQ = QuizQuestion::find($q['id']);
                            $existingQ->update([
                                'type' => $q['type'],
                                'question' => $q['question'],
                                'options' => $q['type'] === 'multiple_choice' ? $q['options'] : null,
                                'correct_answer' => $q['type'] === 'multiple_choice' ? (int) $q['correct_answer'] : null,
                                'explanation' => $q['explanation'] ?? null,
                            ]);
                            $keptQIds[] = $q['id'];
                        } else {
                            $newQ = QuizQuestion::create([
                                'quiz_id' => $quiz->id,
                                'type' => $q['type'],
                                'question' => $q['question'],
                                'options' => $q['type'] === 'multiple_choice' ? $q['options'] : null,
                                'correct_answer' => $q['type'] === 'multiple_choice' ? (int) $q['correct_answer'] : null,
                                'explanation' => $q['explanation'] ?? null,
                            ]);
                            $keptQIds[] = $newQ->id;
                        }
                    }

                    $toDeleteQ = array_diff($existingQIds, $keptQIds);
                    if (!empty($toDeleteQ)) {
                        QuizQuestion::whereIn('id', $toDeleteQ)->delete();
                    }
                } else {
                    $quiz->questions()->delete();
                }
            } else {
                if ($flashcardSet->quiz_id) {
                    $quiz = $flashcardSet->quiz;
                    $flashcardSet->update(['quiz_id' => null]);
                    if ($quiz) {
                        $quiz->questions()->delete();
                        $quiz->delete();
                    }
                }
            }
        });

        return redirect()->route('admin.flashcards.index')->with('success', 'Flashcard set updated successfully.');
    }

    public function destroy(FlashcardSet $flashcard)
    {
        $flashcard->flashcards()->delete();
        $flashcard->delete();
        return redirect()->route('admin.flashcards.index')->with('success', 'Flashcard set deleted successfully.');
    }
}
