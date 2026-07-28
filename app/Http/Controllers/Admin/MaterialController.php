<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::with(['subject', 'user', 'quizzes', 'flashcardSets'])
            ->latest()
            ->paginate(20);

        return view('admin.materials.index', compact('materials'));
    }
}
