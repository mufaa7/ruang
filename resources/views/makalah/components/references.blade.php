{{-- ========================================= --}}
{{-- DAFTAR PUSTAKA --}}
{{-- ========================================= --}}

<section class="a4-page">

    <div class="px-4 sm:px-8 md:px-20 py-8 md:py-16">

        <h2 class="font-bold uppercase text-[14pt] text-center mb-12">
            DAFTAR PUSTAKA
        </h2>

        @if($makalah->references->where('type', '!=', null)->count())

            <div class="space-y-2">

                @foreach($makalah->references->where('type', '!=', null) as $reference)

                    <div class="group relative -mx-4 px-4 py-1.5 hover:bg-stone-50 rounded-lg transition border border-transparent hover:border-stone-200">
                        
                        {{-- Format APA sederhana (preview) --}}
                        <div class="text-[12pt] leading-[1.8] font-['Times_New_Roman'] text-justify" style="text-indent: -36px; padding-left: 36px;">
                            {{ $reference->penulis ?? 'Penulis' }}. ({{ $reference->tahun ?? 'Tahun' }}). <i>{{ $reference->judul ?? 'Judul Referensi' }}</i>. 
                            
                            @if($reference->type == 'buku')
                                {{ $reference->kota_terbit ?? 'Kota' }}: {{ $reference->penerbit ?? 'Penerbit' }}.
                            @elseif($reference->type == 'jurnal')
                                {{ $reference->nama_jurnal ?? 'Jurnal' }}, {{ $reference->volume ?? 'Vol' }}({{ $reference->nomor ?? 'No' }}), {{ $reference->halaman ?? 'Hal' }}.
                            @elseif($reference->type == 'web')
                                Diakses pada {{ $reference->tanggal_akses ?? 'Tanggal' }}, dari {{ $reference->url ?? 'URL' }}
                            @endif
                        </div>

                        {{-- Action buttons --}}
                        <div class="relative sm:absolute sm:right-4 sm:top-4 mt-4 sm:mt-0 opacity-100 lg:opacity-0 group-hover:opacity-100 transition flex gap-2">
                            <span class="hidden sm:inline-block text-xs uppercase font-sans tracking-widest text-stone-400 bg-white px-2 py-1 rounded border mr-2">{{ $reference->type }}</span>
                            
                            <button onclick='openEditReference(@json($reference))' class="flex-1 sm:flex-none px-4 min-h-[44px] text-xs font-sans rounded-xl border bg-white hover:bg-stone-100 active:scale-95 transition-transform">
                                Edit
                            </button>

                            <form action="{{ route('makalah.references.destroy', [$makalah, $reference]) }}" method="POST" class="inline flex-1 sm:flex-none">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 min-h-[44px] text-xs font-sans rounded-xl border border-red-200 bg-white text-red-600 hover:bg-red-50 active:scale-95 transition-transform">
                                    Hapus
                                </button>
                            </form>
                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="mt-4 rounded-xl border-2 border-dashed border-stone-300 p-12 text-center bg-stone-50/50">

                <div class="text-5xl mb-5 opacity-50"><i class="ph ph-books text-[1.1em] align-middle"></i></div>

                <h3 class="text-xl font-bold font-sans">
                    Belum ada referensi
                </h3>

                <p class="text-stone-500 mt-2 font-sans text-sm max-w-sm mx-auto">
                    Daftar pustaka Anda akan diformat otomatis sesuai gaya APA.
                </p>

            </div>

        @endif

        <div class="mt-12 flex flex-col items-center gap-4 border-t border-stone-200 pt-8" x-data="referenceAiGenerator()">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                <button
                    type="button"
                    @click="generateAiReferences(document.querySelector('[data-field=\'judul\']').value, {{ $makalah->id }})"
                    :disabled="generatingAi"
                    class="w-full sm:w-auto justify-center px-6 min-h-[44px] rounded-xl bg-stone-100 border border-stone-300 font-sans text-sm text-neutral-900 hover:bg-stone-200 flex items-center gap-2 transition-all active:scale-95 disabled:opacity-50">
                    <span x-show="!generatingAi"><i class="ph ph-sparkle text-[1.1em] align-middle"></i></span>
                    <span x-show="generatingAi" class="animate-spin" x-cloak>⏳</span>
                    <span x-text="generatingAi ? 'Sedang mikir...' : 'Generate via AI'"></span>
                </button>

                <button
                    type="button"
                    onclick="openAddReference()"
                    class="w-full sm:w-auto justify-center px-6 min-h-[44px] rounded-xl bg-stone-900 font-sans text-sm text-white hover:bg-stone-800 flex items-center gap-2 transition-all active:scale-95">
                    <span>+</span> Tambah Referensi
                </button>
            </div>
            <p x-show="errorMsg" x-text="errorMsg" class="text-xs text-red-500 font-sans" x-cloak></p>
        </div>

    </div>

</section>



{{-- ========================================= --}}
{{-- MODAL TAMBAH REFERENSI --}}
{{-- ========================================= --}}

<div id="reference-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 font-sans">
    <div class="bg-white dark:bg-slate-900 rounded-xl w-full max-w-xl p-8 max-h-[90vh] overflow-y-auto">

        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h2 id="ref-modal-title" class="text-xl font-semibold">Tambah Referensi</h2>
            <button type="button" onclick="closeReferenceModal()" class="text-stone-400 hover:text-stone-700"><i class="ph ph-x text-[1.1em] align-middle"></i></button>
        </div>

        <form id="ref-form" action="{{ route('makalah.references.store', $makalah) }}" method="POST">
            @csrf
            <input type="hidden" name="_method" id="ref-method" value="POST">

            <div class="space-y-4">

                <div>
                    <label class="block mb-2 text-sm font-medium">Jenis Referensi</label>
                    <select id="ref-type-select" name="type" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" onchange="toggleRefFields()">
                        <option value="buku"><i class="ph ph-book text-[1.1em] align-middle"></i> Buku</option>
                        <option value="jurnal"><i class="ph ph-file text-[1.1em] align-middle"></i> Artikel Jurnal</option>
                        <option value="web"><i class="ph ph-globe text-[1.1em] align-middle"></i> Website / Artikel Online</option>
                    </select>
                </div>

                {{-- Field Umum --}}
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-3">
                        <label class="block mb-2 text-sm font-medium">Penulis</label>
                        <input type="text" name="penulis" required class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="Contoh: Doe, J. & Smith, B.">
                    </div>
                    <div class="sm:col-span-1">
                        <label class="block mb-2 text-sm font-medium">Tahun</label>
                        <input type="text" name="tahun" required class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="2024">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium">Judul</label>
                    <input type="text" name="judul" required class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="Judul Buku/Artikel">
                </div>

                {{-- Khusus Buku --}}
                <div id="field-buku" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium">Kota Terbit</label>
                        <input type="text" name="kota_terbit" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="Jakarta">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Penerbit</label>
                        <input type="text" name="penerbit" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="Gramedia">
                    </div>
                </div>

                {{-- Khusus Jurnal --}}
                <div id="field-jurnal" class="hidden space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium">Nama Jurnal</label>
                        <input type="text" name="nama_jurnal" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="Jurnal Ekonomi">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium">Volume</label>
                            <input type="text" name="volume" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="12">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium">Nomor</label>
                            <input type="text" name="nomor" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="2">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium">Halaman</label>
                            <input type="text" name="halaman" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="112-125">
                        </div>
                    </div>
                </div>

                {{-- Khusus Web --}}
                <div id="field-web" class="hidden space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium">URL</label>
                        <input type="url" name="url" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="https://...">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium">Tanggal Akses</label>
                        <input type="text" name="tanggal_akses" class="w-full border border-stone-300 rounded-lg p-2.5 text-sm" placeholder="Contoh: 12 April 2024">
                    </div>
                </div>

            </div>

            <div class="mt-8 flex flex-col-reverse sm:flex-row justify-end gap-3 pt-4 border-t">
                <button type="button" onclick="closeReferenceModal()" class="w-full sm:w-auto px-5 min-h-[44px] rounded-xl border text-sm font-medium hover:bg-stone-50 active:scale-95 transition-all">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-5 min-h-[44px] rounded-xl bg-stone-900 text-white text-sm font-medium hover:bg-stone-800 active:scale-95 transition-all">
                    Simpan Referensi
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function toggleRefFields() {
        const type = document.getElementById('ref-type-select').value;
        document.getElementById('field-buku').classList.toggle('hidden', type !== 'buku');
        document.getElementById('field-jurnal').classList.toggle('hidden', type !== 'jurnal');
        document.getElementById('field-web').classList.toggle('hidden', type !== 'web');
    }

    function openAddReference() {
        document.getElementById('ref-form').reset();
        document.getElementById('ref-form').action = "{{ route('makalah.references.store', $makalah) }}";
        document.getElementById('ref-method').value = "POST";
        document.getElementById('ref-modal-title').innerText = "Tambah Referensi";
        toggleRefFields();
        document.getElementById('reference-modal').classList.remove('hidden');
        document.getElementById('reference-modal').classList.add('flex');
    }

    function openEditReference(ref) {
        document.getElementById('ref-form').reset();
        document.getElementById('ref-form').action = "/makalah/{{ $makalah->id }}/references/" + ref.id;
        document.getElementById('ref-method').value = "PUT";
        document.getElementById('ref-modal-title').innerText = "Edit Referensi";
        
        // Populate fields
        document.getElementById('ref-type-select').value = ref.type;
        document.querySelector('[name="penulis"]').value = ref.penulis || '';
        document.querySelector('[name="tahun"]').value = ref.tahun || '';
        document.querySelector('[name="judul"]').value = ref.judul || '';
        document.querySelector('[name="kota_terbit"]').value = ref.kota_terbit || '';
        document.querySelector('[name="penerbit"]').value = ref.penerbit || '';
        document.querySelector('[name="nama_jurnal"]').value = ref.nama_jurnal || '';
        document.querySelector('[name="volume"]').value = ref.volume || '';
        document.querySelector('[name="nomor"]').value = ref.nomor || '';
        document.querySelector('[name="halaman"]').value = ref.halaman || '';
        document.querySelector('[name="url"]').value = ref.url || '';
        document.querySelector('[name="tanggal_akses"]').value = ref.tanggal_akses || '';
        
        toggleRefFields();
        document.getElementById('reference-modal').classList.remove('hidden');
        document.getElementById('reference-modal').classList.add('flex');
    }

    function closeReferenceModal() {
        document.getElementById('reference-modal').classList.add('hidden');
        document.getElementById('reference-modal').classList.remove('flex');
    }

    // init
    toggleRefFields();

    document.addEventListener('alpine:init', () => {
        Alpine.data('referenceAiGenerator', () => ({
            generatingAi: false,
            errorMsg: '',
            
            async generateAiReferences(topic, makalahId) {
                if(!topic) {
                    alert('Mohon isi judul makalah terlebih dahulu di bagian atas.');
                    return;
                }
                if(!confirm('AI akan meng-generate 3 referensi fiktif namun realistis untuk daftar pustaka ini. Lanjutkan?')) return;
                
                this.generatingAi = true;
                this.errorMsg = '';
                
                try {
                    const res = await fetch(`/api/ai/references/${makalahId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ topic: topic })
                    });
                    
                    const data = await res.json();
                    
                    if(data.success) {
                        window.location.reload(); // Reload to see the new references
                    } else {
                        this.errorMsg = data.message || 'Gagal generate referensi.';
                    }
                } catch (e) {
                    this.errorMsg = 'Koneksi ke server gagal.';
                } finally {
                    this.generatingAi = false;
                }
            }
        }));
    });
</script>