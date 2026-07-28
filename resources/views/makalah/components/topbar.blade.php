<div class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-stone-200 dark:border-slate-700/50">

    <div class="max-w-6xl mx-auto">

        <div class="flex items-center justify-between px-6 py-3">

            {{-- LEFT --}}
            <div class="flex items-center gap-3">

                <a href="{{ route('makalah.index') }}"
                   class="flex items-center gap-2 text-sm text-stone-600 hover:text-black">

                    ←

                    <span>Kembali</span>

                </a>

                <div class="h-5 w-px bg-stone-300"></div>

                <span class="font-semibold">

                    {{ $makalah->judul ?: 'Dokumen Baru' }}

                </span>

            </div>



            {{-- CENTER --}}
            <div class="hidden lg:flex items-center gap-2">

                <button
                    id="btn-image"
                    class="toolbar-btn">

                    🖼️
                    Gambar

                </button>

                <button
                    id="btn-table"
                    class="toolbar-btn">

                    📊
                    Tabel

                </button>

                <button
                    id="btn-equation"
                    class="toolbar-btn">

                    ∑
                    Rumus

                </button>

                <button
                    id="btn-footnote"
                    class="toolbar-btn">

                    ¹
                    Footnote

                </button>

                <button
                    id="btn-reference"
                    class="toolbar-btn">

                    📚
                    Sitasi

                </button>

            </div>



            {{-- RIGHT --}}
            <div class="flex items-center gap-3">

                <div
                    class="hidden xl:flex items-center gap-2
                           bg-emerald-50
                           text-emerald-700
                           px-3 py-1.5
                           rounded-full
                           text-xs">

                    ✔

                    Format Akademik

                </div>

                <button
                    id="btn-ai"
                    class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">

                    ✨

                    Bantu AI

                </button>

            </div>

        </div>

    </div>

</div>



<style>

.toolbar-btn{

    display:flex;

    align-items:center;

    gap:.45rem;

    padding:.55rem .9rem;

    border-radius:.6rem;

    font-size:.85rem;

    border:1px solid #e7e5e4;

    background:white;

    transition:.2s;

}

.toolbar-btn:hover{

    background:#f5f5f4;

}

</style>