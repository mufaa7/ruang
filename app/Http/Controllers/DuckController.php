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
            'event' => 'required|string|max:50'
        ]);

        $dialogue = $this->duckService->getEventDialogue($request->event);

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
            'message' => 'required|string|max:500'
        ]);

        $response = $this->duckService->chat($request->message);

        return response()->json([
            'success' => true,
            'content' => $response
        ]);
    }
}
