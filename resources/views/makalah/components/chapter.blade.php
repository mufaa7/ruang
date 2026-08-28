{{-- ========================================= --}}
{{-- BAB I - VI (DYNAMIC) --}}
{{-- ========================================= --}}

@foreach($makalah->babs as $bab)

<section class="a4-page border-b border-stone-300 relative group/bab chapter-container">

    <div class="absolute top-4 right-4 sm:top-8 sm:right-8 opacity-100 lg:opacity-0 lg:group-hover/bab:opacity-100 transition flex gap-2 z-10">
        <form action="{{ route('makalah.chapters.destroy', [$makalah, $bab]) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus Bab ini beserta semua isinya?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-4 min-h-[44px] text-xs font-sans rounded-xl border border-red-200 bg-white text-red-600 hover:bg-red-50 flex items-center justify-center gap-1 shadow-sm active:scale-95 transition-transform">
                <i class="ph ph-trash text-[1.1em] align-middle"></i>️ Hapus Bab
            </button>
        </form>
    </div>

    <div class="px-4 sm:px-8 md:px-20 py-8 md:py-16 mt-8 sm:mt-0">

        {{-- JUDUL BAB --}}
        <div class="text-center mb-12 flex flex-col items-center group/title">
            <div class="relative w-full flex justify-center">
                <input type="text" 
                    value="{{ $bab->bab_label }}" 
                    class="font-bold uppercase text-lg sm:text-[14pt] text-center w-full max-w-xs bg-transparent border border-transparent hover:border-stone-200 hover:bg-stone-50 focus:border-neutral-800 focus:bg-white transition-all rounded px-2 outline-none auto-save-chapter-title"
                    data-url="{{ route('makalah.chapters.update', [$makalah, $bab]) }}"
                    data-field="bab_label">
                <span class="absolute right-0 sm:right-1/4 top-2 text-stone-300 opacity-100 lg:opacity-0 lg:group-hover/title:opacity-100 transition pointer-events-none"><i class="ph ph-pencil text-[1.1em] align-middle"></i>️</span>
            </div>
            <div class="relative w-full flex justify-center mt-2">
                <input type="text" 
                    value="{{ $bab->title }}" 
                    class="font-bold uppercase text-lg sm:text-[14pt] text-center w-full max-w-md bg-transparent border border-transparent hover:border-stone-200 hover:bg-stone-50 focus:border-neutral-800 focus:bg-white transition-all rounded px-2 outline-none auto-save-chapter-title"
                    data-url="{{ route('makalah.chapters.update', [$makalah, $bab]) }}"
                    data-field="title">
                <span class="absolute right-0 sm:right-1/4 top-2 text-stone-300 opacity-100 lg:opacity-0 lg:group-hover/title:opacity-100 transition pointer-events-none"><i class="ph ph-pencil text-[1.1em] align-middle"></i>️</span>
            </div>
        </div>

        {{-- SUB BAB --}}
        <div class="space-y-12">
            @foreach($bab->subchapters as $i => $sub)
                <div class="relative group/sub" data-subchapter-id="{{ $sub->id }}">
                    
                    <div class="relative flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-2 mb-3 sm:mb-5 group/subtitle">
                        <span class="font-bold text-[12pt] shrink-0 text-stone-400 sm:text-black">{{ $bab->bab_number }}.{{ $i + 1 }}</span>
                        <div class="relative flex-1">
                            <input type="text" 
                                value="{{ $sub->title }}" 
                                class="font-bold text-lg sm:text-[12pt] w-full bg-transparent border border-transparent hover:border-stone-200 hover:bg-stone-50 focus:border-neutral-800 focus:bg-white transition-all rounded sm:px-2 outline-none auto-save-chapter-title"
                                data-url="{{ route('makalah.subchapters.update', [$makalah, $sub]) }}"
                                data-field="title">
                            <span class="absolute right-2 top-1 sm:top-1.5 text-stone-300 opacity-100 lg:opacity-0 lg:group-hover/subtitle:opacity-100 transition pointer-events-none"><i class="ph ph-pencil text-[1.1em] align-middle"></i>️</span>
                        </div>

                        {{-- Badge AI + Tombol Regenerate --}}
                        <div class="flex items-center gap-2 shrink-0 ml-auto mt-2 sm:mt-0">
                            @if($sub->ai_generated)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium bg-violet-100 text-violet-600 border border-violet-200 select-none">
                                    <i class="ph ph-sparkle text-[1.1em] align-middle"></i> AI
                                </span>
                            @endif
                            <button
                                class="btn-regenerate-sub flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-semibold text-stone-700 border border-stone-300 bg-white hover:bg-violet-50 hover:text-violet-700 hover:border-violet-400 transition opacity-100 lg:opacity-0 lg:group-hover/sub:opacity-100 active:scale-95"
                                data-url="{{ route('api.ai.regenerate-subchapter', [$makalah, $sub]) }}"
                                data-csrf="{{ csrf_token() }}"
                                title="Tulis ulang sub-bab ini dengan AI">
                                <i class="ph ph-arrows-clockwise text-[1.1em] align-middle"></i> Tulis Ulang
                            </button>
                        </div>
                    </div>

                    @if(empty(trim($sub->content)) && in_array($makalah->ai_status, ['processing_chapter', 'queued', 'processing_outline']))
                        {{-- Skeleton loading saat AI sedang menulis --}}
                        <div class="skeleton-subchapter animate-pulse p-4 sm:p-6 rounded-xl border border-violet-100 bg-violet-50/50 space-y-3">
                            <div class="h-3 bg-violet-200 rounded w-full"></div>
                            <div class="h-3 bg-violet-200 rounded w-5/6"></div>
                            <div class="h-3 bg-violet-200 rounded w-4/6"></div>
                            <div class="h-3 bg-violet-200 rounded w-full mt-4"></div>
                            <div class="h-3 bg-violet-200 rounded w-3/4"></div>
                            <p class="text-xs text-violet-400 text-center pt-2">⏳ AI sedang menulis bagian ini...</p>
                        </div>
                    @else
                        <div
                            class="chapter-editor auto-save-editor p-4 sm:p-6 rounded-xl border border-stone-200 bg-white"
                            data-subchapter-id="{{ $sub->id }}">
                            {!! $sub->content !!}
                        </div>
                    @endif

                </div>
            @endforeach
        </div>

        {{-- Tambah Sub Bab Button --}}
        <div class="mt-8 flex justify-center opacity-100 lg:opacity-0 lg:group-hover/bab:opacity-100 transition">
            <form action="{{ route('makalah.subchapters.store', [$makalah, $bab]) }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="title" value="Sub-bab Baru">
                <button type="submit" class="w-full sm:w-auto px-4 min-h-[44px] rounded-xl bg-stone-100 font-sans text-sm text-stone-600 hover:bg-stone-200 hover:text-stone-900 transition flex items-center justify-center gap-2 border border-dashed border-stone-300 active:scale-95">
                    <span>+</span> Tambah Sub-bab di {{ $bab->bab_label }}
                </button>
            </form>
        </div>

    </div>

</section>

@endforeach

{{-- Tambah Bab Button --}}
<section class="a4-page bg-stone-50/50 p-6 flex flex-col items-center justify-center min-h-[300px] border-b border-stone-300">
    <div class="text-4xl mb-4 opacity-50"><i class="ph ph-bookmark text-[1.1em] align-middle"></i></div>
    <form action="{{ route('makalah.chapters.store', $makalah) }}" method="POST" class="w-full max-w-xs sm:max-w-none sm:w-auto flex justify-center">
        @csrf
        <input type="hidden" name="type" value="bab">
        <input type="hidden" name="title" value="Bab Baru">
        <button type="submit" class="w-full sm:w-auto px-6 min-h-[44px] rounded-xl bg-stone-900 font-sans text-white hover:bg-stone-800 transition-all flex items-center justify-center gap-2 shadow-lg active:scale-95">
            <span>+</span> Tambah Bab Baru
        </button>
    </form>
    <p class="text-stone-500 font-sans text-sm mt-3 text-center">Bab akan ditambahkan di urutan terakhir</p>
</section>
