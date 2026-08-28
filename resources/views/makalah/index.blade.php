<x-app-layout>
    <x-slot name="pageTitle">Nugas</x-slot>

    <div class="space-y-8 animate-fadeIn mt-4">
        
        {{-- Top Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-white/10">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                    Beban Akademik <span style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif;"><i class="ph ph-notepad text-[1.1em] align-middle"></i></span>
                </h1>
                <p class="text-sm text-slate-300 mt-2">
                    Kalo pusing mending ditinggal tidur aja dulu. Tugasnya nggak bakal lari kemana-mana kok.
                </p>
            </div>

            {{-- Tombol Bikin Makalah --}}
            <a href="{{ route('makalah.create') }}" class="ios-liquid-btn w-full sm:w-auto self-start sm:self-auto min-h-11 px-5 justify-center font-semibold text-sm rounded-xl flex items-center gap-2">
                <i class="ph-bold ph-plus text-lg"></i>
                Bikin Makalah
            </a>
        </div>

        {{-- Grid Makalah --}}
        @if($makalahs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            
            @foreach($makalahs as $m)
                <div class="dashboard-card group flex flex-col min-h-[220px] overflow-hidden cursor-pointer relative p-6 active:scale-[0.98] transition-transform" onclick="window.location='{{ route('makalah.edit', $m) }}'">
                    
                    {{-- Status Indicator (Top Right Dot) --}}
                    <div class="absolute top-6 right-6 flex items-center gap-1.5" title="{{ $m->status === 'final' ? 'Selesai' : 'Draf' }}">
                        <div class="h-2 w-2 rounded-full {{ $m->status === 'final' ? 'bg-emerald-400' : 'bg-amber-400/70' }}"></div>
                        <span class="text-[10px] font-mono text-slate-400 uppercase">{{ $m->status === 'final' ? 'Final' : 'Draf' }}</span>
                    </div>

                    <div class="flex flex-col h-full">
                        <div class="flex flex-col items-start mb-4">
                            <span class="text-[10px] font-bold tracking-widest uppercase text-amber-300/80">
                                {{ $m->mata_kuliah ?: 'Tugas Umum' }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-lg sm:text-xl text-white leading-snug mb-2 group-hover:text-amber-200 transition-colors line-clamp-3">
                            {{ $m->judul ?: 'Tugas Tanpa Judul' }}
                        </h3>

                        <p class="text-xs text-slate-400 mb-6 font-medium">terakhir dibuka {{ $m->updated_at->diffForHumans() }}</p>

                        <div class="mt-auto pt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-0 border-t border-white/10">
                            <div class="flex items-center gap-4 text-xs font-semibold text-slate-300">
                                <span class="flex items-center gap-1.5" title="Jumlah Bab">
                                    <i class="ph ph-file-text text-lg text-slate-400"></i>
                                    {{ $m->chapters->count() }} Bab
                                </span>
                            </div>
                            
                            {{-- Quick Export & Delete Icons (Selalu Muncul di Mobile, Muncul saat hover di Desktop) --}}
                            <div class="flex gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-all translate-y-0 sm:translate-y-2 sm:group-hover:translate-y-0 relative z-10 w-full sm:w-auto justify-between sm:justify-end">
                                <div class="flex gap-2">
                                    <a href="{{ route('makalah.export.pdf', $m) }}" target="_blank" onclick="event.stopPropagation()" class="p-2 sm:p-2.5 text-slate-300 hover:text-white bg-white/5 hover:bg-white/20 rounded-lg transition-colors active:scale-90" title="Export PDF">
                                        <i class="ph ph-file-pdf text-lg sm:text-xl"></i>
                                    </a>
                                    <a href="{{ route('makalah.export.word', $m) }}" data-turbo="false" onclick="event.stopPropagation()" class="p-2 sm:p-2.5 text-slate-300 hover:text-white bg-white/5 hover:bg-white/20 rounded-lg transition-colors active:scale-90" title="Export Word">
                                        <i class="ph ph-file-doc text-lg sm:text-xl"></i>
                                    </a>
                                </div>
                                <form action="{{ route('makalah.destroy', $m) }}" method="POST" class="inline" onclick="event.stopPropagation()" onsubmit="return confirm('Hapus makalah ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 sm:p-2.5 text-slate-400 hover:text-rose-400 bg-white/5 hover:bg-rose-500/20 border border-transparent hover:border-rose-500/30 rounded-lg transition-colors active:scale-90" title="Hapus Makalah">
                                        <i class="ph ph-trash text-lg sm:text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
        @else
            <div class="py-20 flex flex-col items-center justify-center text-center dashboard-card border-dashed border-white/20">
                <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center mb-6 border border-white/10">
                    <i class="ph ph-empty text-3xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Belum ada beban kehidupan di sini <span style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif;"><i class="ph ph-coffee text-[1.1em] align-middle"></i></span></h3>
                <p class="text-slate-300 mt-2 mb-8 max-w-sm">Bikin aja dulu selembar, nggak usah mikir terlalu jauh, yang penting mulai ngetik aja.</p>
                <a href="{{ route('makalah.create') }}" class="w-full sm:w-auto px-6 min-h-11 inline-flex items-center justify-center bg-white/10 hover:bg-white/20 border border-white/10 text-white font-semibold text-sm rounded-xl transition-all active:scale-95 duration-300 backdrop-blur-md hover:scale-105 shadow-sm">
                    Mulai Nulis Makalah
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
