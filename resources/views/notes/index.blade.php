<x-app-layout>
    <x-slot name="pageTitle">Coretan</x-slot>

    <div class="mb-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold text-neutral-900 font-geist dark:text-white">
                Tulis aja apa yang lewat di kepala. ✍️
            </h1>
            <p class="text-sm text-neutral-500 mt-0.5 dark:text-slate-400">gapapa random, ga semua harus jadi makalah.</p>
        </div>
        <button onclick="document.getElementById('modal-new-note').classList.remove('hidden')"
                class="w-full sm:w-auto justify-center px-4 py-2.5 min-h-[44px] bg-neutral-900 text-white font-semibold text-sm rounded-xl hover:bg-neutral-800 shadow-sm transition-all flex items-center gap-2 active:scale-95">
            <i class="ph-bold ph-plus text-lg"></i>
            Coretan Baru
        </button>
    </div>

    @if(session('success'))
    <div class="mb-2 p-3 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 text-sm font-semibold flex items-center gap-2">
        <i class="ph-bold ph-check text-lg shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="h-[calc(100vh-172px)] min-h-[480px] bg-white border border-stone-200 rounded-[24px] overflow-hidden flex flex-col md:flex-row shadow-sm animate-fadeIn dark:bg-slate-900 dark:border-slate-700/50">

        {{-- ========== KIRI: DAFTAR ========== --}}
        <aside id="sidebar-container" class="w-full md:w-72 h-[200px] md:h-auto bg-stone-50 border-b md:border-b-0 md:border-r border-stone-200 flex flex-col shrink-0 overflow-hidden dark:bg-slate-900/50 dark:border-slate-700/50">

            <div class="px-4 pt-3 pb-2.5 border-b border-stone-200 bg-stone-50 shrink-0 dark:border-slate-700/50 dark:bg-slate-900/50">
                <div class="flex items-center justify-between mb-2">
                    @if(request('hashtag'))
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-neutral-700 flex items-center gap-1 dark:text-slate-200">
                            #{{ request('hashtag') }}
                        </span>
                        <a href="{{ route('coretan.index') }}" class="w-4 h-4 flex items-center justify-center rounded-full bg-stone-200 text-neutral-500 hover:text-rose-500 hover:bg-rose-100 transition-colors dark:bg-slate-800 dark:text-slate-400" title="Hapus filter hashtag">
                            <i class="ph-bold ph-x text-[10px]"></i>
                        </a>
                    </div>
                    @else
                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-neutral-500 dark:text-slate-400">Coretan</h3>
                    @endif
                    <span class="text-[10px] font-bold bg-stone-200 text-neutral-700 px-2 py-0.5 rounded-full dark:bg-slate-800 dark:text-slate-200">{{ $notes->count() }}</span>
                </div>
                <div class="flex items-center gap-1.5 hide-scrollbar overflow-x-auto">
                    <a href="{{ route('coretan.index') }}" class="ajax-link px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all shrink-0 {{ !request('filter') && !request('hashtag') ? 'bg-neutral-900 text-white shadow-sm' : 'bg-white border border-stone-200 text-neutral-600 hover:bg-stone-100' }} dark:border-slate-700/50 dark:text-slate-300">Semua</a>
                    <a href="{{ route('coretan.index', ['filter' => 'pinned']) }}" class="ajax-link px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all shrink-0 {{ request('filter') == 'pinned' ? 'bg-neutral-900 text-white shadow-sm' : 'bg-white border border-stone-200 text-neutral-600 hover:bg-stone-100' }} dark:border-slate-700/50 dark:text-slate-300">
                        <i class="ph-fill ph-push-pin text-[10px]"></i> Pin
                    </a>
                    @if(isset($allHashtags) && !empty($allHashtags))
                        @foreach($allHashtags as $tag)
                        <a href="{{ route('coretan.index', ['hashtag' => ltrim($tag, '#')]) }}" class="ajax-link px-3 py-1.5 rounded-xl text-[11px] font-bold transition-all shrink-0 {{ request('hashtag') === ltrim($tag, '#') ? 'bg-neutral-900 text-white shadow-sm' : 'bg-white border border-stone-200 text-neutral-600 hover:bg-stone-100' }} dark:border-slate-700/50 dark:text-slate-300">{{ $tag }}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-2 space-y-1 hide-scrollbar">
                @forelse($notes as $note)
                    @php
                        $isActive = request('note') == $note->id;
                        $hashtags = is_array($note->settings) ? ($note->settings['hashtags'] ?? []) : [];
                        $checklist = is_array($note->settings) ? ($note->settings['checklist'] ?? []) : [];
                        $doneCount = count(array_filter($checklist, fn($c) => $c['done'] ?? false));
                        $totalCount = count($checklist);
                    @endphp

                    <div class="p-3 rounded-[14px] border transition-all text-left block group/card relative {{ $isActive ? 'bg-white border-stone-200 ring-1 ring-neutral-900/10 shadow-sm' : 'border-transparent hover:bg-white hover:border-stone-200 hover:shadow-sm' }} dark:border-slate-700/50">
                        {{-- Link overlay buat seluruh area kartu (kecuali tombol di atasnya) --}}
                        <a href="{{ route('coretan.index', array_merge(request()->query(), ['note' => $note->id])) }}" class="ajax-link absolute inset-0 z-0 rounded-[14px]"></a>
                        
                        <div class="flex items-start justify-between gap-2 mb-0.5 relative z-10">
                            <h4 class="text-sm font-bold text-neutral-900 line-clamp-1 leading-snug dark:text-white">{{ $note->title }}</h4>
                            
                            <div class="flex items-center gap-1.5 shrink-0">
                                {{-- Hashtags --}}
                                @if(!empty($hashtags))
                                    <div class="flex items-center gap-0.5 flex-wrap justify-end">
                                        @foreach(array_slice($hashtags, 0, 3) as $tag)
                                        <a href="{{ route('coretan.index', ['hashtag' => ltrim($tag, '#')]) }}" class="ajax-link text-[10px] font-bold text-neutral-600 border border-stone-200 bg-stone-100 px-1.5 py-0.5 rounded-md hover:bg-stone-200 transition-colors relative z-20 dark:text-slate-300 dark:border-slate-700/50 dark:bg-slate-900/80">{{ $tag }}</a>
                                        @endforeach
                                        @if(count($hashtags) > 3)<span class="text-[9px] text-neutral-400" title="{{ implode(', ', $hashtags) }}">+{{ count($hashtags) - 3 }}</span>@endif
                                    </div>
                                @endif

                                {{-- 📌 Pin toggle --}}
                                <form method="POST" action="{{ route('coretan.pin', $note->id) }}" class="relative z-20">
                                    @csrf
                                    <button type="submit" title="{{ $note->is_pinned ? 'Lepas pin' : 'Pin catatan ini' }}"
                                            class="text-[12px] transition-all {{ $note->is_pinned ? 'opacity-100' : 'opacity-0 group-hover/card:opacity-100 grayscale hover:grayscale-0' }}">
                                        📌
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="text-xs text-neutral-500 line-clamp-1 leading-snug mb-1.5 pr-2 relative z-10 dark:text-slate-400">{{ strip_tags($note->content) ?: '— belum ada isi —' }}</p>

                        <div class="flex items-center justify-between gap-2 flex-wrap relative z-10">
                            <span class="text-[10px] text-neutral-400 flex items-center gap-1">
                                <i class="ph-fill ph-clock text-xs"></i>
                                {{ $note->updated_at->diffForHumans() }}
                            </span>

                            @if($totalCount > 0)
                            <span class="text-[9px] font-bold text-emerald-700 bg-emerald-100 border border-emerald-200 px-1.5 py-0.5 rounded-md">✓ {{ $doneCount }}/{{ $totalCount }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 px-4">
                        <i class="ph-fill ph-empty text-4xl text-neutral-300 mb-2 block"></i>
                        <p class="text-xs text-neutral-400 font-semibold">Belum ada coretan.</p>
                        <p class="text-xs text-neutral-400 mt-0.5">Ada ide apa? Tulis aja.</p>
                    </div>
                @endforelse
            </div>

            @if(isset($trashed) && $trashed->count())
            <div class="p-3 border-t border-stone-200 bg-white shrink-0 dark:border-slate-700/50 dark:bg-slate-900">
                <p class="text-[10px] font-bold uppercase tracking-widest text-neutral-400 mb-1.5 flex items-center gap-1.5">
                    <i class="ph-fill ph-trash text-xs"></i> Sampah ({{ $trashed->count() }})
                </p>
                @foreach($trashed->take(2) as $trash)
                <div class="flex items-center justify-between py-0.5">
                    <span class="text-xs text-neutral-400 line-through truncate pr-2">{{ $trash->title }}</span>
                    <form method="POST" action="{{ route('coretan.restore', $trash->id) }}">
                        @csrf
                        <button class="text-[10px] font-bold text-neutral-500 hover:text-neutral-900 uppercase shrink-0 dark:text-slate-400">Kembaliin</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </aside>

        {{-- ========== KANAN: EDITOR ========== --}}
        <div id="editor-container" class="flex-1 flex flex-col bg-white overflow-hidden dark:bg-slate-900">
            @php
                $activeNote = $notes->firstWhere('id', request('note')) ?? $notes->first();
                $activeHashtags = is_array($activeNote?->settings) ? ($activeNote->settings['hashtags'] ?? []) : [];
                $activeChecklist = is_array($activeNote?->settings) ? ($activeNote->settings['checklist'] ?? []) : [];
            @endphp

            @if($activeNote)
            <form action="{{ route('coretan.update', $activeNote) }}" method="POST" id="note-form" class="h-full flex flex-col relative">
                @csrf
                @method('PATCH')
                <input type="hidden" name="is_pinned" id="pin-input" value="{{ $activeNote->is_pinned ? '1' : '0' }}">
                <input type="hidden" name="checklist_json" id="checklist-json" value="{{ json_encode($activeChecklist) }}">
                <input type="hidden" name="hashtags_json" id="hashtags-json" value="{{ json_encode($activeHashtags) }}">



                {{-- ─── Toolbar atas (super compact) ─────────────────── --}}
                <div class="px-4 sm:px-6 py-3 border-b border-stone-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 bg-white shrink-0 z-10 dark:bg-slate-900">
                    {{-- Hashtag chips + input --}}
                    <div class="flex items-center gap-1.5 flex-1 flex-wrap min-w-0 w-full sm:w-auto">
                        <span class="text-[11px] font-medium text-neutral-500 shrink-0 dark:text-slate-400">Diutak-atik {{ $activeNote->updated_at->diffForHumans() }}</span>
                        @if($activeNote->subject)
                        <span class="text-[10px] font-bold text-neutral-600 bg-stone-100 border border-stone-200 px-2 py-0.5 rounded-md shrink-0 dark:text-slate-300 dark:bg-slate-900/80 dark:border-slate-700/50">{{ $activeNote->subject->name }}</span>
                        @endif
                        <div id="hashtag-display" class="flex items-center gap-1 flex-wrap">
                            @foreach($activeHashtags as $tag)
                            <span class="hashtag-chip text-[11px] font-bold text-neutral-600 bg-stone-100 border border-stone-200 px-2 py-0.5 rounded-md cursor-pointer hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-colors dark:text-slate-300 dark:bg-slate-900/80 dark:border-slate-700/50" onclick="removeHashtag(this)" data-tag="{{ $tag }}">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <input type="text" id="hashtag-input" placeholder="#tag..." onkeydown="handleHashtagKey(event)"
                               class="text-xs font-medium text-neutral-700 bg-transparent border-none outline-none focus:ring-0 placeholder:text-neutral-400 w-20 p-0 shrink-0 dark:text-slate-200" />
                    </div>

                    {{-- Aksi --}}
                    <div class="flex items-center gap-1 shrink-0 w-full sm:w-auto justify-end">
                        {{-- Font size control --}}
                        <button type="button" onclick="changeFontSize(-2)" class="w-9 h-9 sm:w-7 sm:h-7 flex items-center justify-center text-neutral-400 hover:text-neutral-700 hover:bg-stone-100 rounded-lg transition-colors text-xs font-bold active:scale-95">A−</button>
                        <button type="button" onclick="changeFontSize(2)"  class="w-9 h-9 sm:w-7 sm:h-7 flex items-center justify-center text-neutral-400 hover:text-neutral-700 hover:bg-stone-100 rounded-lg transition-colors text-sm font-bold active:scale-95">A+</button>
                        <div class="w-px h-5 bg-stone-200 mx-1 dark:bg-slate-800"></div>
                        {{-- Simpan --}}
                        <button type="submit" id="btn-simpan" class="px-3 py-1.5 min-h-[36px] bg-neutral-900 text-white hover:bg-neutral-800 font-semibold text-xs rounded-xl transition-colors shadow-sm active:scale-95">
                            Simpan
                        </button>
                        {{-- Hapus --}}
                        <button type="button" onclick="if(confirm('Hapus coretan ini?')) document.getElementById('delete-note-form').submit();" class="p-2 sm:p-1.5 min-h-[36px] text-neutral-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition-colors active:scale-95">
                            <i class="ph-bold ph-trash text-base sm:text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- ─── Content area ─────────────────────────────────── --}}
                <div class="flex-1 overflow-y-auto px-6 py-5 hide-scrollbar bg-white dark:bg-slate-900">
                    
                    {{-- Judul --}}
                    <input type="text" name="title" value="{{ $activeNote->title }}"
                           class="block text-2xl font-bold text-neutral-900 font-geist bg-transparent border-none outline-none w-full focus:ring-0 p-0 mb-3 placeholder:text-neutral-300 dark:text-white"
                           placeholder="Judul Coretan..." />

                    {{-- Textarea auto-font --}}
                    <textarea name="content" id="note-content"
                              oninput="handleContentInput(this)"
                              class="block w-full font-serif-editor leading-relaxed text-neutral-800 outline-none resize-none bg-transparent border-none focus:ring-0 p-0 mb-10 placeholder:text-neutral-300 transition-all dark:text-slate-100"
                              style="font-size: {{ strlen(strip_tags($activeNote->content)) < 80 ? '22' : (strlen(strip_tags($activeNote->content)) < 300 ? '17' : '14') }}px; min-height: 240px;"
                              placeholder="Ada ide apa hari ini? Tulis aja...">{{ strip_tags($activeNote->content) }}</textarea>

                    {{-- ─── To-Do List ──────────────────────────────── --}}
                    <div class="border-t border-stone-100 pt-4 mt-2">
                        <p class="text-xs font-bold uppercase tracking-wider text-neutral-500 flex items-center gap-1.5 mb-3 dark:text-slate-400">
                            <i class="ph-bold ph-check-square"></i>
                            To-Do
                        </p>

                        <div class="space-y-2" id="checklist-container">
                            @foreach($activeChecklist as $i => $item)
                            <div class="checklist-item flex items-center gap-2.5 group" data-idx="{{ $i }}">
                                <button type="button" onclick="toggleCheck({{ $i }})"
                                        class="w-5 h-5 rounded-md border-2 shrink-0 flex items-center justify-center transition-all {{ ($item['done'] ?? false) ? 'bg-neutral-900 border-neutral-900 text-white' : 'border-stone-300 hover:border-neutral-400' }}">
                                    @if($item['done'] ?? false)
                                    <i class="ph-bold ph-check text-[10px]"></i>
                                    @endif
                                </button>
                                <span class="text-sm text-neutral-800 flex-1 {{ ($item['done'] ?? false) ? 'line-through text-neutral-400' : '' }} dark:text-slate-100"
                                      contenteditable="true" onblur="updateCheckText({{ $i }}, this)">{{ $item['text'] ?? '' }}</span>
                                <button type="button" onclick="removeCheck({{ $i }})" class="opacity-0 group-hover:opacity-100 text-neutral-300 hover:text-rose-500 transition-all shrink-0">
                                    <i class="ph-bold ph-x text-sm"></i>
                                </button>
                            </div>
                            @endforeach

                            <div class="flex items-center gap-2.5">
                                <div class="w-5 h-5 rounded-md border-2 border-dashed border-stone-300 shrink-0 dark:border-slate-700"></div>
                                <input type="text" id="new-check-input" placeholder="Tambah to-do, tekan Enter..." onkeydown="addCheckOnEnter(event)"
                                       class="text-sm font-medium text-neutral-800 bg-transparent border-none outline-none focus:ring-0 p-0 placeholder:text-neutral-400 flex-1 dark:text-slate-100" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form id="delete-note-form" action="{{ route('coretan.destroy', $activeNote) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>

            @else
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-white dark:bg-slate-900">
                <i class="ph-fill ph-note-pencil text-5xl text-neutral-300 mb-3"></i>
                <h3 class="text-lg font-bold text-neutral-900 font-geist dark:text-white">Belum ada coretan sama sekali</h3>
                <p class="text-sm text-neutral-500 mt-2 max-w-sm dark:text-slate-400">Ga harus bagus, ga harus panjang. Yang penting mulai.</p>
                <button onclick="document.getElementById('modal-new-note').classList.remove('hidden')"
                        class="mt-5 px-6 py-2.5 bg-neutral-900 text-white font-semibold text-sm rounded-xl shadow-sm hover:bg-neutral-800 transition-colors flex items-center gap-2 mx-auto">
                    <i class="ph-bold ph-plus text-lg"></i>
                    Mulai Coret-coret
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal Buat Coretan Baru --}}
    <div id="modal-new-note" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-neutral-900/40 backdrop-blur-sm" onclick="document.getElementById('modal-new-note').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-[24px] max-w-md w-full p-6 shadow-2xl border border-stone-200 z-10 dark:bg-slate-900 dark:border-slate-700/50">
            <div class="flex items-center justify-between pb-4 border-b border-stone-100 mb-5">
                <h3 class="text-lg font-bold text-neutral-900 font-geist dark:text-white">✍️ Bikin coretan baru</h3>
                <button onclick="document.getElementById('modal-new-note').classList.add('hidden')" class="p-1.5 hover:bg-stone-100 rounded-full text-neutral-400 transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            <form action="{{ route('coretan.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2 dark:text-slate-400">Namain dulu coretannya</label>
                    <input type="text" name="title" required placeholder="Judulnya apa?"
                           class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl outline-none text-sm text-neutral-900 focus:border-neutral-500 focus:ring-4 focus:ring-neutral-500/10 transition-all placeholder:text-neutral-400 dark:bg-slate-900/50 dark:border-slate-700/50 dark:text-white" />
                </div>
                <div>
                    <label class="block text-xs font-bold text-neutral-500 uppercase tracking-wider mb-2 dark:text-slate-400">Isi awal <span class="normal-case font-normal text-neutral-400">(boleh kosong dulu)</span></label>
                    <textarea name="content" rows="3" placeholder="Mau nulis apa hari ini?"
                              class="w-full px-4 py-3 bg-stone-50 border border-stone-200 rounded-xl outline-none text-sm text-neutral-900 focus:border-neutral-500 focus:ring-4 focus:ring-neutral-500/10 transition-all placeholder:text-neutral-400 resize-none dark:bg-slate-900/50 dark:border-slate-700/50 dark:text-white"></textarea>
                </div>
                <div class="pt-2 flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-new-note').classList.add('hidden')" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-[44px] bg-stone-100 text-neutral-700 font-semibold text-sm rounded-xl transition-colors hover:bg-stone-200 active:scale-95 dark:bg-slate-900/80 dark:text-slate-200">Ntar dulu</button>
                    <button type="submit" class="w-full sm:w-auto justify-center px-5 py-2.5 min-h-[44px] bg-neutral-900 text-white font-semibold text-sm rounded-xl hover:bg-neutral-800 shadow-sm transition-colors active:scale-95">Yuk, buat!</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        [contenteditable]:focus { outline: none; }
        #note-content { transition: font-size 0.2s ease; }
    </style>

    @push('scripts')
    <script>
    // ─── FONT SIZE AUTO + MANUAL ───────────────────────────────
    var ta = document.getElementById('note-content');
    var manualOverride = false;
    var currentFontSize = ta ? parseInt(ta.style.fontSize) || 17 : 17;

    function autoFont(len) {
        if (manualOverride) return;
        let size;
        if      (len <  60)  size = 26;
        else if (len < 150)  size = 22;
        else if (len < 400)  size = 18;
        else if (len < 800)  size = 16;
        else                 size = 14;
        currentFontSize = size;
        ta.style.fontSize = size + 'px';
    }

    function changeFontSize(delta) {
        manualOverride = true;
        currentFontSize = Math.min(40, Math.max(11, currentFontSize + delta));
        if (ta) ta.style.fontSize = currentFontSize + 'px';
    }

    function handleContentInput(el) {
        // Auto-grow height
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
        // Auto font
        autoFont(el.value.length);
    }

    // Init on load
    if (ta) {
        ta.style.height = 'auto';
        ta.style.height = ta.scrollHeight + 'px';
        autoFont(ta.value.length);
    }

    // ─── AUTO-SAVE (AJAX) ──────────────────────────────────────
    function autoSave() {
        const form = document.getElementById('note-form');
        const btn = document.getElementById('btn-simpan');
        const originalText = btn.innerText;
        btn.innerText = 'Menyimpan...';
        
        fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newSidebar = doc.getElementById('sidebar-container');
            if (newSidebar) {
                document.getElementById('sidebar-container').innerHTML = newSidebar.innerHTML;
            }
            btn.innerText = 'Tersimpan ✓';
            setTimeout(() => btn.innerText = originalText, 2000);
        })
        .catch(err => {
            btn.innerText = 'Gagal Simpan';
            setTimeout(() => btn.innerText = originalText, 2000);
        });
    }

    // ─── HASHTAG ───────────────────────────────────────────────
    var hashtags = @json($activeHashtags);

    function syncHashtagInput() {
        document.getElementById('hashtags-json').value = JSON.stringify(hashtags);
        autoSave();
    }

    function handleHashtagKey(e) {
        if (e.key === 'Enter' || e.key === ' ' || e.key === ',') {
            e.preventDefault();
            const val = e.target.value.trim().replace(/[, ]/g, '');
            if (!val) return;
            const tag = val.startsWith('#') ? val : '#' + val;
            if (!hashtags.includes(tag)) {
                hashtags.push(tag);
                renderHashtag(tag);
                syncHashtagInput();
            }
            e.target.value = '';
        }
    }

    function renderHashtag(tag) {
        const display = document.getElementById('hashtag-display');
        const chip = document.createElement('span');
        chip.className = 'hashtag-chip text-[11px] font-bold text-neutral-600 bg-stone-100 border border-stone-200 px-2 py-0.5 rounded-md cursor-pointer hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition-colors';
        chip.textContent = tag;
        chip.dataset.tag = tag;
        chip.onclick = function() { removeHashtag(this); };
        display.appendChild(chip);
    }

    function removeHashtag(el) {
        hashtags = hashtags.filter(t => t !== el.dataset.tag);
        el.remove();
        syncHashtagInput();
    }

    // ─── CHECKLIST ─────────────────────────────────────────────
    var checklist = @json($activeChecklist);

    function syncChecklist() {
        document.getElementById('checklist-json').value = JSON.stringify(checklist);
        autoSave();
    }

    function toggleCheck(idx) {
        checklist[idx].done = !checklist[idx].done;
        syncChecklist();
        renderAllChecklist();
    }

    function updateCheckText(idx, el) {
        if (checklist[idx]) {
            const newText = el.innerText.trim();
            if (checklist[idx].text !== newText) {
                checklist[idx].text = newText;
                syncChecklist();
            }
        }
    }

    function removeCheck(idx) {
        checklist.splice(idx, 1);
        syncChecklist();
        renderAllChecklist();
    }

    function addCheckOnEnter(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = e.target.value.trim();
            if (!val) return;
            checklist.push({ text: val, done: false });
            syncChecklist();
            e.target.value = '';
            renderAllChecklist();
        }
    }

    function renderAllChecklist() {
        const container = document.getElementById('checklist-container');
        const inputRow  = container.lastElementChild;
        container.querySelectorAll('.checklist-item').forEach(el => el.remove());

        checklist.forEach((item, idx) => {
            const div = document.createElement('div');
            div.className = 'checklist-item flex items-center gap-2.5 group';
            div.dataset.idx = idx;
            const doneBtn = item.done
                ? `<i class="ph-bold ph-check text-[10px]"></i>`
                : '';
            const doneClass = item.done
                ? 'bg-neutral-900 border-neutral-900 text-white'
                : 'border-stone-300 hover:border-neutral-400';
            const textClass = item.done ? 'line-through text-neutral-400' : 'text-neutral-800';
            div.innerHTML = `
                <button type="button" onclick="toggleCheck(${idx})"
                        class="w-5 h-5 rounded-md border-2 shrink-0 flex items-center justify-center transition-all ${doneClass}">
                    ${doneBtn}
                </button>
                <span class="text-sm flex-1 ${textClass}" contenteditable="true" onblur="updateCheckText(${idx}, this)">${item.text}</span>
                <button type="button" onclick="removeCheck(${idx})" class="opacity-0 group-hover:opacity-100 text-neutral-300 hover:text-rose-500 transition-all shrink-0">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>`;
            container.insertBefore(div, inputRow);
        });
    }

    </script>
    @endpush
</x-app-layout>