<?php

namespace App\Http\Controllers;

use App\Models\FlashcardSet;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FlashcardLatihanController extends Controller
{
    public function show(FlashcardSet $flashcardSet): View
    {
        $flashcardSet->load('flashcards');
        
        // Record progress if target exists (not implemented yet because pivot table doesn't have status)
        $user = auth()->user();
        $backUrl = route('dashboard');
        if ($flashcardSet->subject_id) {
            $backUrl = route('subjects.show', $flashcardSet->subject_id);
        }
        
        return view('shared.flashcard', compact('flashcardSet', 'backUrl'));
    }
}
