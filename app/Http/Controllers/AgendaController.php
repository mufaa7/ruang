<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agenda;

class AgendaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date'  => 'required|date',
        ]);

        Agenda::create([
            'user_id' => auth()->id(),
            'title'   => $request->title,
            'date'    => $request->date,
        ]);

        return back()->with('success', 'Agenda berhasil ditambahkan.');
    }

    public function destroy(Agenda $agenda)
    {
        if ($agenda->user_id !== auth()->id()) {
            abort(403);
        }

        $agenda->delete();

        return back()->with('success', 'Agenda berhasil dihapus.');
    }
}
