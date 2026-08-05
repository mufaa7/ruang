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

<div class="inline-flex items-center rounded-full bg-stone-100 text-neutral-900 px-4 py-2 text-sm">

<i class="ph ph-file text-[1.1em] align-middle"></i> Makalah

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

class="w-full rounded-xl border border-stone-200 p-4 resize-none transition-colors focus:ring-2 focus:ring-stone-800 focus:border-neutral-800"

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

class="w-full rounded-xl border border-stone-200 p-3 transition-colors focus:ring-2 focus:ring-stone-800 focus:border-neutral-800"

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

class="w-full rounded-xl border border-stone-200 p-3 transition-colors focus:ring-2 focus:ring-stone-800 focus:border-neutral-800"

placeholder="Ekonomi Makro"

>

</div>

<div>

<label class="block text-sm font-medium mb-2">

Nama Dosen

</label>

<input

name="nama_dosen"

class="w-full rounded-xl border border-stone-200 p-3 transition-colors focus:ring-2 focus:ring-stone-800 focus:border-neutral-800"

placeholder="Dr. Ahmad"

>

</div>

</div>

<div class="rounded-xl bg-stone-50 border border-stone-200 p-5">

<h3 class="font-semibold">

Yang akan dibuat otomatis

</h3>

<div class="grid grid-cols-2 gap-y-2 mt-4 text-sm">

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Cover</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Kata Pengantar</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Daftar Isi</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> BAB I</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> BAB II</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> BAB III</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> BAB IV</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> BAB V</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Penutup</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Daftar Pustaka</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Nomor Halaman</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Format TNR 12</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Margin Akademik</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Export Word</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Export PDF</div>

<div><i class="ph ph-check-circle text-[1.1em] align-middle"></i> Daftar Isi Otomatis</div>

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

class="w-full sm:w-auto px-6 py-3 rounded-xl bg-neutral-900 text-white font-medium hover:bg-stone-700 transition-all active:scale-95"

>

Buat Dokumen

</button>

</div>

</form>

</div>

</x-app-layout>