<x-app-layout>
    <x-slot name="pageTitle">Nugas</x-slot>

    <div class="space-y-8 animate-fadeIn mt-4">
        
        {{-- Top Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 border-b border-stone-200 dark:border-slate-700/50">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-neutral-900 tracking-tight dark:text-white">
                    Beban Akademik <span style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif;"><i class="ph ph-notepad text-[1.1em] align-middle"></i></span>
                </h1>
                <p class="text-sm text-neutral-500 mt-2 dark:text-slate-400">
                    Kalo pusing mending ditinggal tidur aja dulu. Tugasnya nggak bakal lari kemana-mana kok.
                </p>
            </div>

            {{-- Tombol Bikin Makalah --}}
            <a href="{{ route('makalah.create') }}" class="w-full sm:w-auto self-start sm:self-auto min-h-11 px-5 justify-center bg-neutral-900 text-white hover:bg-stone-700 font-medium text-sm rounded-xl flex items-center gap-2 transition-all active:scale-95">
                <i class="ph ph-plus text-lg"></i>
                Bikin Makalah
            </a>
        </div>

        {{-- Grid Makalah --}}
        @if($makalahs->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
            
            @foreach($makalahs as $m)
                <div class="dashboard-card group flex flex-col min-h-[220px] overflow-hidden cursor-pointer relative p-6 active:scale-[0.98] transition-transform" onclick="window.location='{{ route('makalah.edit', $m) }}'">
                    
                    {{-- Status Indicator (Top Right Dot) --}}
                    <div class="absolute top-6 right-6 flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full {{ $m->status === 'final' ? 'bg-emerald-500' : 'bg-neutral-900 animate-pulse' }}"></div>
                    </div>

                    <div class="flex flex-col h-full">
                        <div class="flex flex-col items-start mb-4">
                            <span class="text-[10px] font-bold tracking-widest uppercase text-neutral-400">
                                {{ $m->mata_kuliah ?: 'Tugas Umum' }}
                            </span>
                        </div>
                        
                        <h3 class="font-bold text-lg sm:text-xl text-neutral-900 leading-snug mb-2 group-hover:text-stone-600 transition-colors line-clamp-3 dark:text-white">
                            {{ $m->judul ?: 'Tugas Nggak Jelas Tanpa Judul' }}
                        </h3>

                        <p class="text-xs text-neutral-400 mb-6 font-medium">terakhir dibuka {{ $m->updated_at->diffForHumans() }}</p>

                        <div class="mt-auto pt-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 sm:gap-0 border-t border-stone-100">
                            <div class="flex items-center gap-4 text-xs font-semibold text-neutral-500 dark:text-slate-400">
                                <span class="flex items-center gap-1.5" title="Jumlah Bab">
                                    <i class="ph ph-file-text text-lg text-neutral-400"></i>
                                    {{ $m->chapters->count() }} Bab
                                </span>
                            </div>
                            
                            {{-- Quick Export & Delete Icons (Selalu Muncul di Mobile, Muncul saat hover di Desktop) --}}
                            <div class="flex gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-all translate-y-0 sm:translate-y-2 sm:group-hover:translate-y-0 relative z-10 w-full sm:w-auto justify-between sm:justify-end">
                                <div class="flex gap-2">
                                    <a href="{{ route('makalah.export.pdf', $m) }}" target="_blank" onclick="event.stopPropagation()" class="p-2 sm:p-2.5 text-neutral-400 hover:text-stone-600 bg-stone-50 hover:bg-stone-100 rounded-lg transition-colors active:scale-90 dark:bg-slate-900/50" title="Export PDF">
                                        <i class="ph ph-file-pdf text-lg sm:text-xl"></i>
                                    </a>
                                    <a href="{{ route('makalah.export.word', $m) }}" data-turbo="false" onclick="event.stopPropagation()" class="p-2 sm:p-2.5 text-neutral-400 hover:text-emerald-600 bg-stone-50 hover:bg-emerald-50 rounded-lg transition-colors active:scale-90 dark:bg-slate-900/50" title="Export Word">
                                        <i class="ph ph-file-doc text-lg sm:text-xl"></i>
                                    </a>
                                </div>
                                <form action="{{ route('makalah.destroy', $m) }}" method="POST" class="inline" onclick="event.stopPropagation()" onsubmit="return confirm('Yakin mau buang makalah ini? Ntar nangis nyarinya.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 sm:p-2.5 text-neutral-400 hover:text-red-600 bg-stone-50 hover:bg-red-50 rounded-lg transition-colors active:scale-90 dark:bg-slate-900/50" title="Hapus Makalah">
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
            <div class="py-20 flex flex-col items-center justify-center text-center dashboard-card border-dashed border-stone-300 dark:border-slate-700">
                <div class="w-16 h-16 bg-stone-50 rounded-full flex items-center justify-center mb-6 dark:bg-slate-900/50">
                    <i class="ph ph-empty text-3xl text-neutral-400"></i>
                </div>
                <h3 class="text-xl font-bold text-neutral-900 dark:text-white">Belum ada beban kehidupan di sini <span style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Noto Color Emoji', sans-serif;"><i class="ph ph-coffee text-[1.1em] align-middle"></i></span></h3>
                <p class="text-neutral-500 mt-2 mb-8 max-w-sm dark:text-slate-400">Bikin aja dulu selembar, nggak usah mikir terlalu jauh, yang penting mulai ngetik aja.</p>
                <a href="{{ route('makalah.create') }}" class="w-full sm:w-auto px-6 min-h-11 inline-flex items-center justify-center bg-neutral-900 text-white hover:bg-stone-700 font-medium rounded-xl transition-all active:scale-95">
                    Mulai Nulis Makalah
                </a>
            </div>
        @endif

    </div>
</x-app-layout>
