<!-- reference-fields/thesis.blade.php -->
<div class="space-y-4">
    <div>
        <label class="block text-sm mb-1 text-stone-600">Penulis (Author)</label>
        <input type="text" name="author" class="w-full border rounded-lg p-2.5" placeholder="Contoh: Smith, J.">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm mb-1 text-stone-600">Tahun Lulus</label>
            <input type="number" name="year" class="w-full border rounded-lg p-2.5" placeholder="Contoh: 2024">
        </div>
        <div>
            <label class="block text-sm mb-1 text-stone-600">Jenis</label>
            <select name="thesis_type" class="w-full border rounded-lg p-2.5 bg-white dark:bg-slate-900">
                <option value="Skripsi">Skripsi</option>
                <option value="Tesis">Tesis</option>
                <option value="Disertasi">Disertasi</option>
                <option value="Tugas Akhir">Tugas Akhir</option>
            </select>
        </div>
    </div>
    <div>
        <label class="block text-sm mb-1 text-stone-600">Judul Karya Ilmiah</label>
        <input type="text" name="title" class="w-full border rounded-lg p-2.5" placeholder="Contoh: Analisis Kinerja Sistem...">
    </div>
    <div>
        <label class="block text-sm mb-1 text-stone-600">Nama Universitas/Institusi</label>
        <input type="text" name="university" class="w-full border rounded-lg p-2.5" placeholder="Contoh: Universitas Indonesia">
    </div>
</div>
