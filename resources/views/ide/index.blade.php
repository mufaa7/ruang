<x-app-layout>
    <x-slot name="pageTitle">Kotak Ide</x-slot>
    <x-slot name="pageSubtitle">RUANG masih terus berkembang. Kalau nemu bug, punya ide, atau sekadar ingin ngobrol, tinggal tulis di bawah.</x-slot>

    <div class="animate-fadeIn pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6">
            
            {{-- Left Column: Form (Span 8) --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- Form Container --}}
                <div class="dashboard-card p-6 sm:p-8 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm rounded-[24px] border border-[#D6D0C4] dark:border-slate-800 shadow-[0_4px_24px_rgba(0,0,0,0.02)] transition-all hover:shadow-[0_8px_32px_rgba(0,0,0,0.04)]" x-data="{
                        category: 'ide',
                        isDragging: false,
                        fileName: '',
                        handleDrop(e) {
                            this.isDragging = false;
                            if (e.dataTransfer.files.length > 0) {
                                this.fileName = e.dataTransfer.files[0].name;
                            }
                        },
                        handleFile(e) {
                            if (e.target.files.length > 0) {
                                this.fileName = e.target.files[0].name;
                            }
                        }
                    }">
                    
                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-800 dark:text-emerald-400 text-sm flex items-start gap-3">
                            <i class="ph-fill ph-check-circle text-xl shrink-0 mt-0.5"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 text-rose-800 dark:text-rose-400 text-sm flex items-start gap-3">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('ide.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" @submit="isDragging = false">
                        @csrf
                        {{-- Kategori --}}
                        <div>
                            <label class="block text-sm font-medium text-[#4F4A44] dark:text-slate-300 mb-4">Pilih Kategori</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="category" value="bug" x-model="category" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-800 transition-all peer-checked:border-[#1F1F1D] dark:peer-checked:border-indigo-500 peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-bug text-xl text-neutral-500 group-hover:text-rose-500 peer-checked:text-rose-600 transition-colors dark:text-slate-400"></i>
                                            <span class="text-sm font-medium text-[#1F1F1D] dark:text-white">Lapor Bug</span>
                                        </div>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-transparent peer-checked:border-[#1F1F1D]/10 dark:peer-checked:border-indigo-500/20 rounded-xl pointer-events-none transition-all"></div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="category" value="ide" x-model="category" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-800 transition-all peer-checked:border-[#1F1F1D] dark:peer-checked:border-indigo-500 peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-lightbulb text-xl text-neutral-500 group-hover:text-amber-500 peer-checked:text-amber-600 transition-colors dark:text-slate-400"></i>
                                            <span class="text-sm font-medium text-[#1F1F1D] dark:text-white">Ide Baru</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="category" value="fitur" x-model="category" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-800 transition-all peer-checked:border-[#1F1F1D] dark:peer-checked:border-indigo-500 peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-sparkle text-xl text-neutral-500 group-hover:text-indigo-500 peer-checked:text-indigo-600 transition-colors dark:text-slate-400"></i>
                                            <span class="text-sm font-medium text-[#1F1F1D] dark:text-white">Request Fitur</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="category" value="uiux" x-model="category" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-800 transition-all peer-checked:border-[#1F1F1D] dark:peer-checked:border-indigo-500 peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-palette text-xl text-neutral-500 group-hover:text-fuchsia-500 peer-checked:text-fuchsia-600 transition-colors dark:text-slate-400"></i>
                                            <span class="text-sm font-medium text-[#1F1F1D] dark:text-white">Masukan UI/UX</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="category" value="tanya" x-model="category" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-800 transition-all peer-checked:border-[#1F1F1D] dark:peer-checked:border-indigo-500 peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-chat-circle-question text-xl text-neutral-500 group-hover:text-sky-500 peer-checked:text-sky-600 transition-colors dark:text-slate-400"></i>
                                            <span class="text-sm font-medium text-[#1F1F1D] dark:text-white">Pertanyaan</span>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="category" value="ngobrol" x-model="category" class="peer sr-only">
                                    <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 dark:hover:bg-slate-800 transition-all peer-checked:border-[#1F1F1D] dark:peer-checked:border-indigo-500 peer-checked:bg-white dark:peer-checked:bg-slate-800 peer-checked:shadow-sm">
                                        <div class="flex items-center gap-2">
                                            <i class="ph ph-coffee text-xl text-neutral-500 group-hover:text-orange-500 peer-checked:text-orange-600 transition-colors dark:text-slate-400"></i>
                                            <span class="text-sm font-medium text-[#1F1F1D] dark:text-white">Ngobrol Santai</span>
                                        </div>
                                    </div>
                                </label>
                                
                            </div>
                        </div>

                        {{-- Input Judul --}}
                        <div>
                            <label class="block text-sm font-medium text-[#4F4A44] dark:text-slate-300 mb-2">Judul</label>
                            <input type="text" name="title" placeholder="Singkat, padat, jelas" required
                                   class="w-full rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/60 dark:bg-slate-800/50 px-4 min-h-11 text-sm text-[#1F1F1D] dark:text-white focus:border-[#1F1F1D] dark:focus:border-indigo-500 focus:ring-1 focus:ring-[#1F1F1D] dark:focus:ring-indigo-500 transition-all shadow-sm placeholder:text-[#A8A296] dark:placeholder:text-slate-500">
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-sm font-medium text-[#4F4A44] dark:text-slate-300 mb-2">Ceritain Detailnya</label>
                            <textarea name="description" rows="4" required
                                      placeholder="Contoh:&#10;- Editor blank setelah refresh.&#10;- Akan keren kalau ada export Markdown.&#10;- Sidebar terlalu sempit di tablet."
                                      class="w-full rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/60 dark:bg-slate-800/50 p-4 text-sm text-[#1F1F1D] dark:text-white focus:border-[#1F1F1D] dark:focus:border-indigo-500 focus:ring-1 focus:ring-[#1F1F1D] dark:focus:ring-indigo-500 transition-all shadow-sm placeholder:text-[#A8A296] dark:placeholder:text-slate-500 resize-y"></textarea>
                        </div>

                        {{-- Drag Drop Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-[#4F4A44] dark:text-slate-300 mb-2">Upload Screenshot (Opsional)</label>
                            <div class="relative w-full rounded-xl border-2 border-dashed border-[#D6D0C4] dark:border-slate-700 bg-white/30 dark:bg-slate-800/30 p-8 text-center transition-all hover:bg-white/60 dark:hover:bg-slate-800/60 hover:border-[#A8A296] dark:hover:border-slate-500 cursor-pointer"
                                 :class="{ 'border-[#1F1F1D] bg-[#EFECE5]/50 dark:border-indigo-500 dark:bg-indigo-500/10': isDragging }"
                                 @dragover.prevent="isDragging = true"
                                 @dragleave.prevent="isDragging = false"
                                 @drop.prevent="handleDrop($event)">
                                 
                                <input type="file" name="screenshot" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" @change="handleFile($event)">
                                
                                <div class="flex flex-col items-center justify-center gap-2 pointer-events-none">
                                    <i class="ph ph-image text-3xl text-[#7C756C] dark:text-slate-400"></i>
                                    <p class="text-sm font-medium text-[#4F4A44] dark:text-slate-300" x-text="fileName ? fileName : 'Tarik foto ke sini atau klik'"></p>
                                    <p class="text-[11px] text-[#A8A296] dark:text-slate-500" x-show="!fileName">PNG, JPG, GIF up to 5MB</p>
                                </div>
                            </div>
                        </div>

                        {{-- Kontak --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[13px] font-medium text-[#4F4A44] dark:text-slate-300 mb-2">Email (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-envelope-simple text-[#A8A296] dark:text-slate-500"></i>
                                    </div>
                                    <input type="email" name="email" placeholder="Buat ngabarin update"
                                           class="w-full rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/60 dark:bg-slate-800/50 pl-9 pr-4 h-10 text-sm text-[#1F1F1D] dark:text-white focus:border-[#1F1F1D] dark:focus:border-indigo-500 focus:ring-1 focus:ring-[#1F1F1D] transition-all shadow-sm placeholder:text-[#A8A296]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-medium text-[#4F4A44] dark:text-slate-300 mb-2">Discord / Telegram (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph ph-at text-[#A8A296] dark:text-slate-500"></i>
                                    </div>
                                    <input type="text" name="contact" placeholder="username"
                                           class="w-full rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/60 dark:bg-slate-800/50 pl-9 pr-4 h-10 text-sm text-[#1F1F1D] dark:text-white focus:border-[#1F1F1D] dark:focus:border-indigo-500 focus:ring-1 focus:ring-[#1F1F1D] transition-all shadow-sm placeholder:text-[#A8A296]">
                                </div>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-2">
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#1F1F1D] dark:bg-indigo-600 px-6 min-h-[48px] text-sm font-semibold text-[#F7F5F1] dark:text-white transition-all hover:bg-[#34302C] dark:hover:bg-indigo-500 active:scale-[0.98] shadow-lg shadow-[#1F1F1D]/10">
                                Kirim Ide
                                <i class="ph ph-paper-plane-tilt"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Right Column: Roadmap & Changelog (Span 4) --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- Sedang Dikerjakan --}}
                <div class="dashboard-card p-6 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm rounded-[24px] border border-[#D6D0C4] dark:border-slate-800 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#1F1F1D] dark:text-white uppercase tracking-wider mb-5 flex items-center gap-2">
                        <i class="ph-fill ph-hammer text-[#4F4A44]"></i> Sedang Kami Kerjakan
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 transition-colors">
                            <h4 class="text-sm font-medium text-[#1F1F1D] dark:text-white flex items-center gap-2">
                                🚧 Focus Mode
                            </h4>
                            <p class="text-xs text-[#7C756C] dark:text-slate-400 mt-1">
                                Sedang dikembangkan. Nggak ada gangguan pas lagi nulis.
                            </p>
                        </div>

                        <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 transition-colors">
                            <h4 class="text-sm font-medium text-[#1F1F1D] dark:text-white flex items-center gap-2">
                                🚧 Collaborative Workspace
                            </h4>
                            <p class="text-xs text-[#7C756C] dark:text-slate-400 mt-1">
                                Dalam tahap riset. Biar bisa nugas bareng.
                            </p>
                        </div>

                        <div class="p-3 rounded-xl border border-[#D6D0C4] dark:border-slate-700 bg-white/40 dark:bg-slate-800/50 hover:bg-white/80 transition-colors">
                            <h4 class="text-sm font-medium text-[#1F1F1D] dark:text-white flex items-center gap-2">
                                🚧 Export Markdown
                            </h4>
                            <p class="text-xs text-[#7C756C] dark:text-slate-400 mt-1">
                                Segera hadir. Bawa datamu ke mana aja.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Perubahan Terbaru --}}
                <div class="dashboard-card p-6 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm rounded-[24px] border border-[#D6D0C4] dark:border-slate-800 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#1F1F1D] dark:text-white uppercase tracking-wider mb-5 flex items-center gap-2">
                        <i class="ph-fill ph-clock-counter-clockwise text-[#4F4A44]"></i> Perubahan Terbaru
                    </h3>
                    
                    <div class="relative pl-4 border-l-2 border-[#D6D0C4] dark:border-slate-700 space-y-5">
                        <div class="relative">
                            <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-[#1F1F1D] dark:bg-indigo-500 ring-4 ring-[#F7F5F1] dark:ring-slate-900"></div>
                            <span class="text-[10px] font-bold tracking-widest text-[#7C756C] dark:text-slate-400 uppercase bg-[#EFECE5] dark:bg-slate-800 px-2 py-0.5 rounded-md">v1.2.0</span>
                            <ul class="mt-2 space-y-1.5 text-[13px] text-[#4F4A44] dark:text-slate-300">
                                <li class="flex items-start gap-2">
                                    <i class="ph ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                                    Auto Save jauh lebih cepat & aman
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="ph ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                                    Perbaikan sesi Login yang sering putus
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="ph ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                                    Optimasi loading Dashboard
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="ph ph-check text-emerald-600 mt-0.5 shrink-0"></i>
                                    Bug sidebar nyangkut di tablet diperbaiki
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- FAQ Sederhana --}}
                <div class="dashboard-card p-6 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm rounded-[24px] border border-[#D6D0C4] dark:border-slate-800 shadow-sm">
                    <h3 class="text-sm font-semibold text-[#1F1F1D] dark:text-white uppercase tracking-wider mb-4">
                        FAQ Cepat
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <h5 class="text-[13px] font-semibold text-[#1F1F1D] dark:text-white">Berapa lama feedback dibalas?</h5>
                            <p class="text-[12px] text-[#7C756C] dark:text-slate-400 mt-1">Biasanya dalam 1-2 hari kerja kalo ninggalin email/kontak.</p>
                        </div>
                        <div>
                            <h5 class="text-[13px] font-semibold text-[#1F1F1D] dark:text-white">Apakah semua request fitur dibikin?</h5>
                            <p class="text-[12px] text-[#7C756C] dark:text-slate-400 mt-1">Nggak semua, tergantung prioritas dan seberapa banyak yang butuh fitur yang sama.</p>
                        </div>
                        <div>
                            <h5 class="text-[13px] font-semibold text-[#1F1F1D] dark:text-white">Bisa ngirim screenshot error?</h5>
                            <p class="text-[12px] text-[#7C756C] dark:text-slate-400 mt-1">Bisa banget, tinggal drag & drop aja di form upload sebelah.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-12 text-center">
            <p class="text-[12px] font-medium text-[#A8A296] dark:text-slate-500">
                Dibuat sendirian, jadi kalau nemu bug jangan langsung marah ya 🙂
            </p>
        </div>
    </div>
</x-app-layout>
