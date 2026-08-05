{{-- ========================================================= --}}
{{-- DAFTAR ISI --}}
{{-- ========================================================= --}}

<section class="a4-page border-b border-stone-300">

    <div class="px-4 sm:px-8 md:px-20 py-8 md:py-16">

        <div class="flex items-center justify-between mb-10">

            <h2 class="text-[14pt] font-bold uppercase">
                Daftar Isi
            </h2>

            <span
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs bg-sky-100 text-sky-700">

                <i class="ph ph-arrows-clockwise text-[1.1em] align-middle"></i> Otomatis

            </span>

        </div>

        <p class="text-sm text-stone-500 mb-8">

            Daftar isi akan diperbarui otomatis saat dokumen diekspor.

        </p>

        <div class="space-y-4 text-[12pt] leading-8">

            <div class="flex items-center">
                <span>Kata Pengantar</span>
                <div class="flex-1 border-b border-dotted border-stone-400 mx-2"></div>
                <span class="text-stone-400 italic">Auto</span>
            </div>

            <div class="flex items-center">
                <span>Daftar Isi</span>
                <div class="flex-1 border-b border-dotted border-stone-400 mx-2"></div>
                <span class="text-stone-400 italic">Auto</span>
            </div>

            @foreach($makalah->chapters->where('type', 'bab') as $bab)
            <div class="flex items-center">
                <span>{{ $bab->bab_label }} {{ $bab->title }}</span>
                <div class="flex-1 border-b border-dotted border-stone-400 mx-2"></div>
                <span class="text-stone-400 italic">Auto</span>
            </div>
            @endforeach

            <div class="flex items-center">
                <span>Daftar Pustaka</span>
                <div class="flex-1 border-b border-dotted border-stone-400 mx-2"></div>
                <span class="text-stone-400 italic">Auto</span>
            </div>

        </div>

        <div class="mt-12 rounded-xl bg-stone-100 p-5">

            <div class="flex items-start gap-3">

                <span class="text-xl"><i class="ph ph-lightbulb text-[1.1em] align-middle"></i></span>

                <div>

                    <h4 class="font-semibold">

                        Tidak perlu mengedit halaman ini.

                    </h4>

                    <p class="text-sm text-stone-600 mt-1">

                        Nomor halaman, heading, dan daftar isi akan dibuat otomatis saat Export Word atau PDF.

                    </p>

                </div>

            </div>

        </div>

    </div>

</section>