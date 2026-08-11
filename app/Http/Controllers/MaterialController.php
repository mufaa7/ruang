<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    public function store(Request $request, Subject $subject)
    {
        if (!auth()->user()->isAdmin() && !$subject->users()->where('users.id', auth()->id())->exists() && auth()->id() !== $subject->created_by) {
            abort(403, 'Anda harus bergabung dengan kelas ini terlebih dahulu untuk mengunggah materi.');
        }
        $request->validate([
            'title' => 'nullable|string|max:255',
            'file' => 'required_without:content|nullable|file|mimes:pdf,ppt,pptx,doc,docx|max:10240', // max 10MB
            'content' => 'required_without:file|nullable|string',
        ], [
            'file.required_without' => 'Silakan unggah file atau masukkan teks materi.',
            'content.required_without' => 'Silakan unggah file atau masukkan teks materi.',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'status' => 'pending',
        ];

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['title'] = $request->input('title') ?: $file->getClientOriginalName();
            $data['file_path'] = $file->store('materials', 'public');
            $data['file_type'] = $file->getClientOriginalExtension();
            $data['file_size'] = $file->getSize() / 1024;
        } else {
            $data['title'] = $request->input('title');
            $data['content'] = $request->input('content');
            $data['file_type'] = 'text';
            $data['file_size'] = strlen($request->input('content')) / 1024;
        }

        // Simpan database
        $material = $subject->materials()->create($data);

        return redirect()->back()->with('success', 'Materi berhasil ditambahkan!');
    }
    
    public function destroy(Subject $subject, Material $material)
    {
        $this->authorize('delete', $material);
        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();
        
        return redirect()->back()->with('success', 'Materi berhasil dihapus.');
    }

    public function update(Request $request, Subject $subject, Material $material)
    {
        $this->authorize('update', $material);

        $request->validate([
            'content' => 'required|string',
        ]);

        $material->update([
            'content' => $request->input('content'),
        ]);

        return redirect()->back()->with('success', 'Teks materi berhasil diperbarui!');
    }
}
