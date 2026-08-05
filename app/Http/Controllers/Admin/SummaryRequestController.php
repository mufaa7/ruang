<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\SummaryRequest;
use Illuminate\Http\Request;

class SummaryRequestController extends Controller
{
    public function index()
    {
        $requests = SummaryRequest::with(['user', 'subject', 'material', 'note'])
            ->latest()
            ->paginate(20);

        return view('admin.summary-requests.index', compact('requests'));
    }

    public function fulfill(SummaryRequest $summaryRequest)
    {
        if ($summaryRequest->status === 'completed') {
            return redirect()->route('admin.summary_requests.index')
                ->with('error', 'Request ini sudah dikerjakan.');
        }

        return view('admin.summary-requests.fulfill', compact('summaryRequest'));
    }

    public function storeNote(Request $request, SummaryRequest $summaryRequest)
    {
        if ($summaryRequest->status === 'completed') {
            return redirect()->route('admin.summary_requests.index')->with('error', 'Sudah dikerjakan.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $note = Note::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'user_id' => $summaryRequest->user_id,
            'subject_id' => $summaryRequest->subject_id,
            'is_ai_generated' => true, // Menandai bahwa ini (pura-pura) dari AI
        ]);

        $summaryRequest->update([
            'status' => 'completed',
            'note_id' => $note->id,
        ]);

        return redirect()->route('admin.summary_requests.index')
            ->with('success', 'Rangkuman berhasil dikirim ke user!');
    }
}
