<x-app-layout>

<x-slot name="pageTitle">
Makalah Baru
</x-slot>

<x-slot name="pageSubtitle">
Mulai dari judul. Struktur, cover, dan format akan dibuat otomatis.
</x-slot>

<div class="max-w-3xl">

<form method="POST"
action="{{ route('makalah.store') }}"
class="space-y-6">

@csrf

<div class="rounded-2xl border border-stone-200 bg-white dark:bg-slate-900 p-6 sm:p-8">

<div class="space-y-8">

<div>

<h2 class="font-semibold text-xl text-stone-900">

Dokumen Baru

</h2>

<p class="text-sm text-stone-500 mt-1">

RUANG akan membuat struktur makalah secara otomatis.

</p>

</div>

<div>

<label class="text-sm font-medium text-stone-700">

Jenis Dokumen

</label>

<div class="mt-2">

<div class="inline-flex items-center rounded-full bg-indigo-50 text-indigo-700 px-4 py-2 text-sm">

📄 Makalah

</div>

<p class="mt-2 text-xs text-stone-500">

Jenis dokumen bisa diubah nanti dari editor.

</p>

</div>

</div>

<div>

<label class="block text-sm font-medium mb-2">

Judul

</label>

<textarea

name="judul"

rows="2"

required

class="w-full rounded-xl border border-stone-200 p-4 resize-none transition-colors focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"

placeholder="Contoh : Analisis Inflasi Indonesia Tahun 2025"

></textarea>

</div>

<div>

<label class="block text-sm font-medium mb-2">

Sub Judul

<span class="text-stone-400">

(Opsional)

</span>

</label>

<input

name="sub_judul"

class="w-full rounded-xl border border-stone-200 p-3 transition-colors focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"

placeholder="Opsional"

>

</div>

<div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

<div>

<label class="block text-sm font-medium mb-2">

Mata Kuliah

</label>

<input

name="mata_kuliah"

class="w-full rounded-xl border border-stone-200 p-3 transition-colors focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"

placeholder="Ekonomi Makro"

>

</div>

<div>

<label class="block text-sm font-medium mb-2">

Nama Dosen

</label>

<input

name="nama_dosen"

class="w-full rounded-xl border border-stone-200 p-3 transition-colors focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"

placeholder="Dr. Ahmad"

>

</div>

</div>

<div class="rounded-xl bg-stone-50 border border-stone-200 p-5">

<h3 class="font-semibold">

Yang akan dibuat otomatis

</h3>

<div class="grid grid-cols-2 gap-y-2 mt-4 text-sm">

<div>✅ Cover</div>

<div>✅ Kata Pengantar</div>

<div>✅ Daftar Isi</div>

<div>✅ BAB I</div>

<div>✅ BAB II</div>

<div>✅ BAB III</div>

<div>✅ BAB IV</div>

<div>✅ BAB V</div>

<div>✅ Penutup</div>

<div>✅ Daftar Pustaka</div>

<div>✅ Nomor Halaman</div>

<div>✅ Format TNR 12</div>

<div>✅ Margin Akademik</div>

<div>✅ Export Word</div>

<div>✅ Export PDF</div>

<div>✅ Daftar Isi Otomatis</div>

</div>

</div>

</div>

</div>

<div class="flex flex-col-reverse sm:flex-row justify-end gap-3">

<a

href="{{ route('makalah.index') }}"

class="w-full sm:w-auto px-5 py-3 rounded-xl border border-stone-200 text-center font-medium hover:bg-stone-50 transition-all active:scale-95"

>

Batal

</a>

<button

class="w-full sm:w-auto px-6 py-3 rounded-xl bg-neutral-900 text-white font-medium hover:bg-neutral-800 transition-all active:scale-95"

>

Buat Dokumen

</button>

</div>

</form>

</div>

</x-app-layout>