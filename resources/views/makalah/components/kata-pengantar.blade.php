{{-- ========================================================= --}}
{{-- KATA PENGANTAR --}}
{{-- ========================================================= --}}

<section class="a4-page border-b border-stone-300">

    <div class="px-4 sm:px-8 md:px-20 py-8 md:py-16">

        <div class="flex items-center justify-between mb-10">

            <h2 class="text-[14pt] font-bold uppercase tracking-wide">
                Kata Pengantar
            </h2>

            <div class="flex gap-2">

                <button
                    type="button"
                    id="clear-preface"
                    class="px-4 min-h-[44px] inline-flex items-center text-sm rounded-lg border border-stone-300 hover:bg-stone-100 transition-all active:scale-95">

                    Kosongkan

                </button>

            </div>

        </div>

        <div
            id="preface-editor"
            class="quill-editor"
            data-section="kata_pengantar">

            {!! $makalah->kata_pengantar !!}

        </div>

    </div>

</section>