<!-- reference-fields/website.blade.php -->
<div class="space-y-4">
    <div>
        <label class="block text-sm mb-1 text-stone-600">Penulis / Instansi</label>
        <input type="text" name="author" class="w-full border rounded-lg p-2.5" placeholder="Contoh: Kementerian Keuangan">
    </div>
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm mb-1 text-stone-600">Tahun</label>
            <input type="number" name="year" class="w-full border rounded-lg p-2.5" placeholder="Contoh: 2024">
        </div>
        <div>
            <label class="block text-sm mb-1 text-stone-600">Tanggal Akses</label>
            <input type="date" name="access_date" class="w-full border rounded-lg p-2.5">
        </div>
    </div>
    <div>
        <label class="block text-sm mb-1 text-stone-600">Judul Artikel / Halaman</label>
        <input type="text" name="title" class="w-full border rounded-lg p-2.5" placeholder="Contoh: Laporan APBN 2024">
    </div>
    <div>
        <label class="block text-sm mb-1 text-stone-600">Nama Website</label>
        <input type="text" name="website_name" class="w-full border rounded-lg p-2.5" placeholder="Contoh: Kemenkeu RI">
    </div>
    <div>
        <label class="block text-sm mb-1 text-stone-600">URL (Tautan)</label>
        <input type="url" name="url" class="w-full border rounded-lg p-2.5" placeholder="https://...">
    </div>
</div>
