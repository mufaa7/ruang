<x-app-layout>
    <x-slot name="pageTitle">Makalah Baru</x-slot>
    <x-slot name="pageSubtitle">Mulai dari judul. Struktur, cover, dan format akan dibuat otomatis.</x-slot>

    <div class="max-w-3xl mt-4 animate-fadeIn">
        <form method="POST" action="{{ route('makalah.store') }}" class="space-y-6">
            @csrf
            
            <div class="dashboard-card p-6 sm:p-8">
                <div class="space-y-8">
                    
                    <div>
                        <h2 class="font-semibold text-xl text-white">Dokumen Baru</h2>
                        <p class="text-sm text-slate-300 mt-1">RUANG akan membuat struktur makalah secara otomatis.</p>
                    </div>

                    <div>
                        <label class="text-sm font-medium text-slate-300">Jenis Dokumen</label>
                        <div class="mt-2">
                            <div class="inline-flex items-center rounded-full bg-white/10 border border-white/10 text-white px-4 py-2 text-sm">
                                <i class="ph ph-file text-[1.1em] align-middle mr-1.5"></i> Makalah
                            </div>
                            <p class="mt-2 text-xs text-slate-400">Jenis dokumen bisa diubah nanti dari editor.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-300">Judul</label>
                        <textarea name="judul" rows="2" required class="w-full rounded-xl bg-black/30 border-none p-4 text-white placeholder-white/40 resize-none transition-colors focus:ring-2 focus:ring-amber-400/50" placeholder="Contoh : Analisis Inflasi Indonesia Tahun 2025"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-2 text-slate-300">
                            Sub Judul <span class="text-slate-500">(Opsional)</span>
                        </label>
                        <input name="sub_judul" class="w-full rounded-xl bg-black/30 border-none p-3 text-white placeholder-white/40 transition-colors focus:ring-2 focus:ring-amber-400/50" placeholder="Opsional">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-300">Mata Kuliah</label>
                            <input name="mata_kuliah" class="w-full rounded-xl bg-black/30 border-none p-3 text-white placeholder-white/40 transition-colors focus:ring-2 focus:ring-amber-400/50" placeholder="Ekonomi Makro">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2 text-slate-300">Nama Dosen</label>
                            <input name="nama_dosen" class="w-full rounded-xl bg-black/30 border-none p-3 text-white placeholder-white/40 transition-colors focus:ring-2 focus:ring-amber-400/50" placeholder="Dr. Ahmad">
                        </div>
                    </div>

                    <div class="rounded-xl bg-white/5 border border-white/10 p-5 text-slate-300">
                        <h3 class="font-semibold text-white">Yang akan dibuat otomatis</h3>
                        <div class="grid grid-cols-2 gap-y-3 mt-5 text-sm">
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Cover</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Kata Pengantar</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Daftar Isi</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> BAB I</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> BAB II</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> BAB III</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> BAB IV</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> BAB V</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Penutup</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Daftar Pustaka</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Nomor Halaman</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Format TNR 12</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Margin Akademik</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Export Word</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Export PDF</div>
                            <div><i class="ph ph-check-circle text-amber-300 align-middle mr-1.5 text-base"></i> Daftar Isi Otomatis</div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pb-8">
                <a href="{{ route('makalah.index') }}" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-white/5 border border-white/10 text-white text-center font-medium hover:bg-white/10 transition-all active:scale-95">
                    Batal
                </a>
                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-white text-black font-medium hover:bg-neutral-200 shadow-lg transition-all active:scale-95">
                    Buat Dokumen
                </button>
            </div>
            
        </form>
    </div>
</x-app-layout>