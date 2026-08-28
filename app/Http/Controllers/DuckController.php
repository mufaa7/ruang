<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\DuckService;

class DuckController extends Controller
{
    protected DuckService $duckService;

    public function __construct(DuckService $duckService)
    {
        $this->duckService = $duckService;
    }

    /**
     * Handle event-based automated talk.
     */
    public function event(Request $request)
    {
        $request->validate([
            'event' => 'required|string|max:50',
            'page_title' => 'nullable|string|max:200',
            'page_url' => 'nullable|string|max:200',
        ]);

        $dialogue = $this->duckService->getEventDialogue($request->event, $request->page_title, $request->page_url);

        return response()->json([
            'success' => true,
            'content' => $dialogue
        ]);
    }

    /**
     * Handle direct two-way chat from the user.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'history' => 'nullable|array|max:60',
        ]);

        $response = $this->duckService->chat(
            $request->message,
            $request->input('history', [])
        );

        return response()->json([
            'success' => true,
            'content' => $response
        ]);
    }
}
