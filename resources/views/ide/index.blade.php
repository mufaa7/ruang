<x-app-layout>
    <x-slot name="pageTitle">Kotak Ide</x-slot>
    <x-slot name="pageSubtitle">RUANG masih terus berkembang. Kalau nemu bug, punya ide, atau sekadar ingin ngobrol, tinggal tulis di bawah.</x-slot>

    <div class="animate-fadeIn pb-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mt-6">
            
            {{-- Left Column: Form (Span 8) --}}
            <div class="lg:col-span-8 space-y-6">
                
                {{-- Form Container --}}
                <div class="p-6 sm:p-8 bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 shadow-2xl relative overflow-hidden" x-data="{
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
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        @if(session('success'))
                            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm flex items-start gap-3 shadow-inner">
                                <i class="ph-fill ph-check-circle text-xl shrink-0 mt-0.5"></i>
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-sm flex items-start gap-3 shadow-inner">
                                <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif

                        <form action="{{ route('ide.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" @submit="isDragging = false">
                            @csrf
                            {{-- Kategori --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-4">Pilih Kategori</label>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    
                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="category" value="bug" x-model="category" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-rose-500/50 peer-checked:bg-rose-500/10 peer-checked:shadow-[0_0_15px_rgba(244,63,94,0.1)]">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-bug text-xl text-slate-500 group-hover:text-rose-400 peer-checked:text-rose-500 transition-colors"></i>
                                                <span class="text-sm font-bold text-slate-300 peer-checked:text-white">Lapor Bug</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="category" value="ide" x-model="category" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-amber-500/50 peer-checked:bg-amber-500/10 peer-checked:shadow-[0_0_15px_rgba(245,158,11,0.1)]">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-lightbulb text-xl text-slate-500 group-hover:text-amber-400 peer-checked:text-amber-500 transition-colors"></i>
                                                <span class="text-sm font-bold text-slate-300 peer-checked:text-white">Ide Baru</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="category" value="fitur" x-model="category" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-blue-500/50 peer-checked:bg-blue-500/10 peer-checked:shadow-[0_0_15px_rgba(59,130,246,0.1)]">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-sparkle text-xl text-slate-500 group-hover:text-blue-400 peer-checked:text-blue-500 transition-colors"></i>
                                                <span class="text-sm font-bold text-slate-300 peer-checked:text-white">Request Fitur</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="category" value="uiux" x-model="category" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-fuchsia-500/50 peer-checked:bg-fuchsia-500/10 peer-checked:shadow-[0_0_15px_rgba(217,70,239,0.1)]">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-palette text-xl text-slate-500 group-hover:text-fuchsia-400 peer-checked:text-fuchsia-500 transition-colors"></i>
                                                <span class="text-sm font-bold text-slate-300 peer-checked:text-white">Masukan UI/UX</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="category" value="tanya" x-model="category" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-sky-500/50 peer-checked:bg-sky-500/10 peer-checked:shadow-[0_0_15px_rgba(14,165,233,0.1)]">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-chat-circle-question text-xl text-slate-500 group-hover:text-sky-400 peer-checked:text-sky-500 transition-colors"></i>
                                                <span class="text-sm font-bold text-slate-300 peer-checked:text-white">Pertanyaan</span>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer group">
                                        <input type="radio" name="category" value="ngobrol" x-model="category" class="peer sr-only">
                                        <div class="p-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-all peer-checked:border-orange-500/50 peer-checked:bg-orange-500/10 peer-checked:shadow-[0_0_15px_rgba(249,115,22,0.1)]">
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-coffee text-xl text-slate-500 group-hover:text-orange-400 peer-checked:text-orange-500 transition-colors"></i>
                                                <span class="text-sm font-bold text-slate-300 peer-checked:text-white">Ngobrol Santai</span>
                                            </div>
                                        </div>
                                    </label>
                                    
                                </div>
                            </div>

                            {{-- Input Judul --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Judul</label>
                                <input type="text" name="title" placeholder="Singkat, padat, jelas" required
                                       class="w-full rounded-xl border border-white/10 bg-white/5 px-4 min-h-[44px] text-sm text-white focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all shadow-inner placeholder:text-slate-500">
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Ceritain Detailnya</label>
                                <textarea name="description" rows="5" required
                                          placeholder="Contoh:&#10;- Editor blank setelah refresh.&#10;- Akan keren kalau ada export Markdown.&#10;- Sidebar terlalu sempit di tablet."
                                          class="w-full rounded-xl border border-white/10 bg-white/5 p-4 text-sm text-white focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all shadow-inner placeholder:text-slate-500 resize-y"></textarea>
                            </div>

                            {{-- Drag Drop Upload --}}
                            <div>
                                <label class="block text-sm font-bold text-slate-300 mb-2">Upload Screenshot (Opsional)</label>
                                <div class="relative w-full rounded-xl border-2 border-dashed border-white/20 bg-white/5 p-8 text-center transition-all hover:bg-white/10 hover:border-white/40 cursor-pointer"
                                     :class="{ 'border-amber-500/50 bg-amber-500/10': isDragging }"
                                     @dragover.prevent="isDragging = true"
                                     @dragleave.prevent="isDragging = false"
                                     @drop.prevent="handleDrop($event)">
                                     
                                    <input type="file" name="screenshot" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" @change="handleFile($event)">
                                    
                                    <div class="flex flex-col items-center justify-center gap-3 pointer-events-none">
                                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center shadow-inner">
                                            <i class="ph-bold ph-image text-xl text-slate-300"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-white" x-text="fileName ? fileName : 'Tarik foto ke sini atau klik'"></p>
                                            <p class="text-[11px] font-medium text-slate-500 mt-1" x-show="!fileName">PNG, JPG, GIF up to 5MB</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Kontak --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-300 mb-2">Email (Opsional)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ph-fill ph-envelope-simple text-slate-400"></i>
                                        </div>
                                        <input type="email" name="email" placeholder="Buat ngabarin update"
                                               class="w-full rounded-xl border border-white/10 bg-white/5 pl-9 pr-4 h-[44px] text-sm text-white focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all shadow-inner placeholder:text-slate-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-300 mb-2">Discord / Telegram (Opsional)</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <i class="ph-bold ph-at text-slate-400"></i>
                                        </div>
                                        <input type="text" name="contact" placeholder="username"
                                               class="w-full rounded-xl border border-white/10 bg-white/5 pl-9 pr-4 h-[44px] text-sm text-white focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all shadow-inner placeholder:text-slate-500">
                                    </div>
                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="pt-4 border-t border-white/10">
                                <button type="submit"
                                        class="w-full flex items-center justify-center gap-2 rounded-xl bg-white/10 border border-white/10 px-6 min-h-[48px] text-sm font-bold text-white transition-all hover:bg-white/20 active:scale-95 shadow-lg">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> Kirim Ide
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Right Column: Roadmap & Changelog (Span 4) --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- Sedang Dikerjakan --}}
                <div class="p-6 bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                            <i class="ph-fill ph-hammer text-slate-500"></i> Sedang Kami Kerjakan
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors shadow-inner">
                                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                    <i class="ph-fill ph-traffic-cone text-amber-500"></i> Focus Mode
                                </h4>
                                <p class="text-xs font-medium text-slate-400 mt-1.5 leading-relaxed">
                                    Sedang dikembangkan. Nggak ada gangguan pas lagi nulis.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors shadow-inner">
                                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                    <i class="ph-fill ph-traffic-cone text-amber-500"></i> Collaborative Workspace
                                </h4>
                                <p class="text-xs font-medium text-slate-400 mt-1.5 leading-relaxed">
                                    Dalam tahap riset. Biar bisa nugas bareng.
                                </p>
                            </div>

                            <div class="p-4 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition-colors shadow-inner">
                                <h4 class="text-sm font-bold text-white flex items-center gap-2">
                                    <i class="ph-fill ph-traffic-cone text-amber-500"></i> Export Markdown
                                </h4>
                                <p class="text-xs font-medium text-slate-400 mt-1.5 leading-relaxed">
                                    Segera hadir. Bawa datamu ke mana aja.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Perubahan Terbaru --}}
                <div class="p-6 bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5 flex items-center gap-2">
                            <i class="ph-bold ph-clock-counter-clockwise text-slate-500"></i> Perubahan Terbaru
                        </h3>
                        
                        <div class="relative pl-5 border-l border-white/10 space-y-6">
                            <div class="relative">
                                <div class="absolute -left-[25px] top-1 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-black/40"></div>
                                <span class="text-[10px] font-bold tracking-widest text-white uppercase bg-white/10 border border-white/10 px-2 py-1 rounded-md shadow-inner">v1.2.0</span>
                                <ul class="mt-3 space-y-2 text-[13px] text-slate-300">
                                    <li class="flex items-start gap-2">
                                        <i class="ph-bold ph-check text-emerald-500 mt-0.5 shrink-0"></i>
                                        Auto Save jauh lebih cepat & aman
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="ph-bold ph-check text-emerald-500 mt-0.5 shrink-0"></i>
                                        Perbaikan sesi Login yang sering putus
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="ph-bold ph-check text-emerald-500 mt-0.5 shrink-0"></i>
                                        Optimasi loading Dashboard
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="ph-bold ph-check text-emerald-500 mt-0.5 shrink-0"></i>
                                        Bug sidebar nyangkut di tablet diperbaiki
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FAQ Sederhana --}}
                <div class="p-6 bg-black/40 backdrop-blur-xl rounded-[24px] border border-white/10 shadow-2xl relative overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>
                    <div class="relative z-10">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-5">
                            FAQ Cepat
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <h5 class="text-[13px] font-bold text-white">Berapa lama feedback dibalas?</h5>
                                <p class="text-[12px] font-medium text-slate-400 mt-1.5 leading-relaxed">Biasanya dalam 1-2 hari kerja kalo ninggalin email/kontak.</p>
                            </div>
                            <div>
                                <h5 class="text-[13px] font-bold text-white">Apakah semua request fitur dibikin?</h5>
                                <p class="text-[12px] font-medium text-slate-400 mt-1.5 leading-relaxed">Nggak semua, tergantung prioritas dan seberapa banyak yang butuh fitur yang sama.</p>
                            </div>
                            <div>
                                <h5 class="text-[13px] font-bold text-white">Bisa ngirim screenshot error?</h5>
                                <p class="text-[12px] font-medium text-slate-400 mt-1.5 leading-relaxed">Bisa banget, tinggal drag & drop aja di form upload sebelah.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        {{-- Footer --}}
        <div class="mt-12 text-center">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">
                Dibuat sendirian, jadi kalau nemu bug jangan langsung marah ya 🙂
            </p>
        </div>
    </div>
</x-app-layout>
