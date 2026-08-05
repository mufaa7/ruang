{{-- ========================= --}}
{{-- COVER --}}
{{-- ========================= --}}

<section class="a4-page px-4 sm:px-8 md:px-20 py-8 md:py-16 text-center border-b border-stone-300 relative">

    {{-- Generate Full Makalah Button --}}
    <div class="mb-12 font-sans border-b border-stone-200 pb-8">
        <button type="button" id="btn-generate-full" class="px-6 py-3 rounded-full bg-gradient-to-r from-neutral-800 to-purple-600 text-white font-bold text-sm shadow-lg hover:shadow-xl hover:scale-105 transition transform inline-flex items-center gap-2">
            <span><i class="ph ph-sparkle text-[1.1em] align-middle"></i></span> Generate Isi Makalah dengan AI
        </button>
        <p class="text-xs text-stone-400 mt-3">Isi judul makalah di bawah, lalu klik tombol ini. AI akan meracik seluruh isinya (Bab I - III) untukmu.</p>
    </div>

    {{-- Jenis Dokumen --}}
    <input 
        type="text"
        class="block w-full mx-auto mb-8 text-[16pt] font-bold uppercase border-none bg-transparent text-center focus:ring-0 p-0 m-0 auto-save text-black"
        data-field="jenis_dokumen"
        placeholder="MAKALAH / PROPOSAL / LAPORAN"
        value="{{ $makalah->jenis_dokumen ?: 'MAKALAH' }}">



    {{-- Judul --}}
    <textarea
        rows="3"
        class="block w-full mx-auto text-center text-[20pt] font-bold uppercase border-none bg-transparent resize-none focus:ring-0 p-0 m-0 overflow-hidden auto-save text-black"
        data-field="judul"
        placeholder="Judul Dokumen">

{{ $makalah->judul }}

</textarea>



    {{-- Logo --}}
    <div class="my-12">
        <img
            src="{{ asset('Universitas_Trilogi_logo.png') }}"
            class="mx-auto h-[332px] object-contain"
            alt="Logo">
    </div>

    {{-- Info Blok (Mata Kuliah, Dosen, Penulis) --}}
    <div class="flex flex-col items-center justify-center space-y-8 mt-12 text-[12pt] text-black leading-relaxed">
        
        {{-- Mata Kuliah --}}
        <div>
            <p>Disusun untuk Memenuhi Tugas Mata Kuliah</p>
            <input
                type="text"
                class="cover-input auto-save font-normal mt-1"
                data-field="mata_kuliah"
                value="{{ $makalah->mata_kuliah }}"
                placeholder="Nama Mata Kuliah">
        </div>

        {{-- Dosen Pengampu --}}
        <div>
            <p>Dosen Pengampu :</p>
            <input
                type="text"
                class="cover-input auto-save font-normal mt-1"
                data-field="nama_dosen"
                value="{{ $makalah->nama_dosen }}"
                placeholder="Nama Dosen">
        </div>

        {{-- Disusun Oleh --}}
        <div class="pt-4 flex flex-col items-center">
            <p class="font-bold mb-2">Disusun Oleh :</p>
            <p class="font-normal">{{ auth()->user()->name }}</p>
            <div class="flex items-center gap-1 mt-1 justify-center">
                <span class="font-normal">NIM :</span>
                <input
                    type="text"
                    class="auto-save font-normal bg-transparent border-none outline-none focus:ring-0 p-0 m-0 focus:bg-gray-100/50 rounded text-center"
                    style="field-sizing: content; min-width: 60px; max-width: 200px;"
                    data-field="nim"
                    value="{{ $makalah->nim }}"
                    placeholder="00000">
            </div>
        </div>

    </div>

    {{-- Footer Cover --}}
    <div class="mt-20 space-y-1 uppercase font-bold text-[14pt] leading-relaxed flex flex-col items-center">
        <p>PROGRAM STUDI EKONOMI PEMBANGUNAN</p>
        <p>UNIVERSITAS TRILOGI</p>
        <p>{{ date('Y') }}</p>
    </div>

</section>



<style>

.a4-page{

    font-family:"Times New Roman",serif;

    font-size:12pt;

}

.cover-input{

    width:100%;

    text-align:center;

    border:none;

    padding:0;

    margin:0;

    background:transparent;

    outline:none;

    box-shadow:none;

    font-size:12pt;

}

.cover-input:focus{

    background: rgba(243, 244, 246, 0.5);
    border-radius: 4px;

}

</style>