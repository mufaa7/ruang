<x-app-layout>
    <x-slot name="pageTitle">Coretan</x-slot>

    <div class="mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-fadeIn">
        <div>
            <h1 class="text-3xl font-bold text-white font-geist tracking-tight">
                Tulis aja apa yang lewat di kepala. <i class="ph ph-signature text-emerald-400 align-middle"></i>
            </h1>
            <p class="text-sm text-slate-400 mt-1">gapapa random, ga semua harus jadi makalah.</p>
        </div>
        <button onclick="document.getElementById('modal-new-note').classList.remove('hidden')"
                class="w-full sm:w-auto justify-center px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/10 text-white font-semibold text-sm rounded-xl transition-all flex items-center gap-2 backdrop-blur-md hover:scale-105 active:scale-95 duration-300">
            <i class="ph-bold ph-plus text-lg"></i>
            Coretan Baru
        </button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-4 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20 text-sm font-semibold flex items-center gap-3 backdrop-blur-md animate-fadeIn">
        <i class="ph-bold ph-check-circle text-xl shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif

    <div class="md:h-[calc(100vh-200px)] min-h-[480px] bg-black/40 border border-white/10 rounded-[24px] overflow-hidden flex flex-col md:flex-row backdrop-blur-xl shadow-2xl animate-fadeIn relative">
        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent pointer-events-none"></div>

        {{-- ========== KIRI: DAFTAR ========== --}}
        <aside id="sidebar-container" class="w-full md:w-[320px] lg:w-[360px] h-[200px] md:h-auto border-b md:border-b-0 md:border-r border-white/10 flex flex-col shrink-0 overflow-hidden relative z-10 bg-black/20">

            <div class="px-5 pt-4 pb-3 border-b border-white/10 shrink-0">
                <div class="flex items-center justify-between mb-3">
                    @if(request('hashtag'))
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] font-bold uppercase tracking-widest text-emerald-400 flex items-center gap-1">
                            #{{ request('hashtag') }}
                        </span>
                        <a href="{{ route('coretan.index') }}" class="w-5 h-5 flex items-center justify-center rounded-full bg-white/10 text-slate-300 hover:text-white hover:bg-rose-500/30 transition-colors" title="Hapus filter hashtag">
                            <i class="ph-bold ph-x text-[10px]"></i>
                        </a>
                    </div>
                    @else
                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-slate-400">Coretan</h3>
                    @endif
                    <span class="text-[10px] font-bold bg-white/10 text-white px-2.5 py-0.5 rounded-full">{{ $notes->count() }}</span>
                </div>
                <div class="flex items-center gap-2 hide-scrollbar overflow-x-auto pb-1">
                    <a href="{{ route('coretan.index') }}" class="ajax-link px-3.5 py-1.5 rounded-xl text-[11px] font-bold transition-all shrink-0 {{ !request('filter') && !request('hashtag') ? 'bg-white/15 text-white border border-white/20' : 'bg-transparent border border-white/10 text-slate-400 hover:bg-white/10 hover:text-white' }}">Semua</a>
                    <a href="{{ route('coretan.index', ['filter' => 'pinned']) }}" class="ajax-link px-3.5 py-1.5 rounded-xl text-[11px] font-bold transition-all shrink-0 flex items-center gap-1 {{ request('filter') == 'pinned' ? 'bg-white/15 text-white border border-white/20' : 'bg-transparent border border-white/10 text-slate-400 hover:bg-white/10 hover:text-white' }}">
                        <i class="ph-fill ph-push-pin text-[12px]"></i> Pin
                    </a>
                    @if(isset($allHashtags) && !empty($allHashtags))
                        @foreach($allHashtags as $tag)
                        <a href="{{ route('coretan.index', ['hashtag' => ltrim($tag, '#')]) }}" class="ajax-link px-3.5 py-1.5 rounded-xl text-[11px] font-bold transition-all shrink-0 {{ request('hashtag') === ltrim($tag, '#') ? 'bg-white/15 text-white border border-white/20' : 'bg-transparent border border-white/10 text-slate-400 hover:bg-white/10 hover:text-white' }}">{{ $tag }}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-3 space-y-2 hide-scrollbar">
                @forelse($notes as $note)
                    @php
                        $isActive = request('note') == $note->id;
                        $hashtags = is_array($note->settings) ? ($note->settings['hashtags'] ?? []) : [];
                        $checklist = is_array($note->settings) ? ($note->settings['checklist'] ?? []) : [];
                        $doneCount = count(array_filter($checklist, fn($c) => $c['done'] ?? false));
                        $totalCount = count($checklist);
                    @endphp

                    <div class="p-4 rounded-[16px] border transition-all duration-300 text-left block group/card relative {{ $isActive ? 'bg-white/10 border-white/20 shadow-[0_0_20px_rgba(255,255,255,0.05)]' : 'bg-transparent border-transparent hover:bg-white/5 hover:border-white/10' }}">
                        <a href="{{ route('coretan.index', array_merge(request()->query(), ['note' => $note->id])) }}" class="ajax-link absolute inset-0 z-10 rounded-[16px]"></a>
                        
                        <div class="flex items-start justify-between gap-3 mb-1.5 relative z-0 pointer-events-none">
                            <h4 class="text-sm font-bold text-white line-clamp-1 leading-snug">{{ $note->title }}</h4>
                            
                            <div class="flex items-center gap-2 shrink-0 pointer-events-auto">
                                @if(!empty($hashtags))
                                    <div class="flex items-center gap-1 flex-wrap justify-end">
                                        @foreach(array_slice($hashtags, 0, 2) as $tag)
                                        <a href="{{ route('coretan.index', ['hashtag' => ltrim($tag, '#')]) }}" class="ajax-link text-[9px] font-bold text-emerald-300 border border-emerald-500/30 bg-emerald-500/10 px-1.5 py-0.5 rounded-md hover:bg-emerald-500/20 transition-colors relative z-20">{{ $tag }}</a>
                                        @endforeach
                                        @if(count($hashtags) > 2)<span class="text-[9px] text-slate-500 font-medium" title="{{ implode(', ', $hashtags) }}">+{{ count($hashtags) - 2 }}</span>@endif
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('coretan.pin', $note->id) }}" class="relative z-20">
                                    @csrf
                                    <button type="submit" title="{{ $note->is_pinned ? 'Lepas pin' : 'Pin catatan ini' }}"
                                            class="text-[14px] transition-all duration-200 {{ $note->is_pinned ? 'text-amber-300' : 'text-slate-500 opacity-0 group-hover/card:opacity-100 hover:text-white' }}">
                                        <i class="{{ $note->is_pinned ? 'ph-fill' : 'ph' }} ph-push-pin align-middle"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mb-3 relative z-0 pointer-events-none">{{ strip_tags($note->content) ?: '— belum ada isi —' }}</p>

                        <div class="flex items-center justify-between gap-2 flex-wrap relative z-0 pointer-events-none">
                            <span class="text-[10px] text-slate-500 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-clock text-xs text-slate-600"></i>
                                {{ $note->updated_at->diffForHumans() }}
                            </span>

                            @if($totalCount > 0)
                            <span class="text-[9px] font-bold text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded-md flex items-center gap-1">
                                <i class="ph-bold ph-list-checks"></i> {{ $doneCount }}/{{ $totalCount }}
                            </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 px-4 flex flex-col items-center justify-center h-full">
                        <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center mb-3 border border-white/10">
                            <i class="ph-fill ph-ghost text-2xl text-slate-500"></i>
                        </div>
                        <p class="text-sm text-slate-300 font-semibold mb-1">Sepi banget...</p>
                        <p class="text-xs text-slate-500">Belum ada coretan sama sekali.</p>
                    </div>
                @endforelse
            </div>

            @if(isset($trashed) && $trashed->count())
            <div class="p-4 border-t border-white/10 bg-black/40 shrink-0 backdrop-blur-md">
                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-2.5 flex items-center gap-1.5">
                    <i class="ph-fill ph-trash text-xs"></i> Tong Sampah ({{ $trashed->count() }})
                </p>
                @foreach($trashed->take(2) as $trash)
                <div class="flex items-center justify-between py-1 group/trash">
                    <span class="text-xs text-slate-500 line-through truncate pr-3 group-hover/trash:text-slate-400 transition-colors">{{ $trash->title }}</span>
                    <form method="POST" action="{{ route('coretan.restore', $trash->id) }}">
                        @csrf
                        <button class="text-[10px] font-bold text-emerald-500/70 hover:text-emerald-400 uppercase shrink-0 transition-colors bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">Pulihkan</button>
                    </form>
                </div>
                @endforeach
            </div>
            @endif
        </aside>

        {{-- ========== KANAN: EDITOR ========== --}}
        <div id="editor-container" class="flex-1 flex flex-col overflow-hidden relative z-10">
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

                {{-- ─── Toolbar atas ─────────────────── --}}
                <div class="px-5 sm:px-8 py-4 border-b border-white/10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white/5 backdrop-blur-md shrink-0">
                    <div class="flex items-center gap-2 flex-1 flex-wrap min-w-0 w-full sm:w-auto">
                        <span class="text-[11px] font-medium text-slate-400 shrink-0"><i class="ph-bold ph-pencil-simple line-middle mr-1"></i> {{ $activeNote->updated_at->diffForHumans() }}</span>
                        
                        <div class="w-px h-3 bg-white/20 mx-1"></div>

                        <div id="hashtag-display" class="flex items-center gap-1.5 flex-wrap">
                            @foreach($activeHashtags as $tag)
                            <span class="hashtag-chip text-[11px] font-bold text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-md cursor-pointer hover:bg-rose-500/20 hover:text-rose-400 hover:border-rose-500/30 transition-colors" onclick="removeHashtag(this)" data-tag="{{ $tag }}">{{ $tag }}</span>
                            @endforeach
                        </div>
                        <input type="text" id="hashtag-input" placeholder="+ tambah tag" onkeydown="handleHashtagKey(event)"
                               class="text-[11px] font-medium text-slate-300 bg-white/5 border border-transparent focus:border-white/20 rounded-md outline-none px-2 py-0.5 focus:bg-white/10 placeholder:text-slate-500 w-24 shrink-0 transition-all" />
                    </div>

                    <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto justify-end">
                        <button type="button" onclick="changeFontSize(-2)" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-lg transition-all text-xs font-bold active:scale-95" title="Perkecil Teks">A−</button>
                        <button type="button" onclick="changeFontSize(2)"  class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-lg transition-all text-sm font-bold active:scale-95" title="Perbesar Teks">A+</button>
                        
                        <div class="w-px h-5 bg-white/10 mx-1"></div>
                        
                        <button type="submit" id="btn-simpan" class="px-4 py-1.5 min-h-[32px] bg-white text-black hover:bg-slate-200 font-bold text-xs rounded-lg transition-all shadow-sm active:scale-95 flex items-center gap-1.5">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan
                        </button>
                        
                        <button type="button" onclick="if(confirm('Hapus coretan ini ke tong sampah?')) document.getElementById('delete-note-form').submit();" class="p-1.5 ml-1 text-slate-400 hover:text-rose-400 hover:bg-rose-500/20 rounded-lg transition-colors active:scale-95">
                            <i class="ph-bold ph-trash text-lg"></i>
                        </button>
                    </div>
                </div>

                {{-- ─── Content area ─────────────────────────────────── --}}
                <div class="flex-1 overflow-y-auto px-6 sm:px-10 py-8 hide-scrollbar">
                    
                    <input type="text" name="title" value="{{ $activeNote->title }}"
                           class="block text-3xl sm:text-4xl font-bold text-white font-geist bg-transparent border-none outline-none w-full focus:ring-0 p-0 mb-6 placeholder:text-slate-500 transition-colors"
                           placeholder="Judul Coretan..." />

                    <textarea name="content" id="note-content"
                              oninput="handleContentInput(this)"
                              class="block w-full font-sans leading-[1.8] text-slate-200 outline-none resize-none bg-transparent border-none focus:ring-0 p-0 mb-12 placeholder:text-slate-500 transition-colors selection:bg-amber-400/20"
                              style="font-size: {{ strlen(strip_tags($activeNote->content)) < 80 ? '22' : (strlen(strip_tags($activeNote->content)) < 300 ? '17' : '15') }}px; min-height: 280px;"
                              placeholder="Ada ide apa hari ini? Tulis aja semuanya di sini...">{{ strip_tags($activeNote->content) }}</textarea>

                    {{-- ─── To-Do List ──────────────────────────────── --}}
                    <div class="border-t border-white/10 pt-6 mt-4 pb-8">
                        <p class="text-[11px] font-bold uppercase tracking-widest text-slate-400 flex items-center gap-2 mb-4 font-mono">
                            <i class="ph-bold ph-check-square-offset text-base text-emerald-400"></i>
                            Checklist / To-Do
                        </p>

                        <div class="space-y-3" id="checklist-container">
                            @foreach($activeChecklist as $i => $item)
                            <div class="checklist-item flex items-start gap-3 group" data-idx="{{ $i }}">
                                <button type="button" onclick="toggleCheck({{ $i }})"
                                        class="w-5 h-5 mt-0.5 rounded-[6px] border-2 shrink-0 flex items-center justify-center transition-all duration-200 {{ ($item['done'] ?? false) ? 'bg-emerald-500 border-emerald-500 text-white' : 'border-slate-600 hover:border-slate-400 bg-white/5' }}">
                                    @if($item['done'] ?? false)
                                    <i class="ph-bold ph-check text-[10px]"></i>
                                    @endif
                                </button>
                                <span class="text-[15px] flex-1 leading-relaxed outline-none transition-all duration-200 {{ ($item['done'] ?? false) ? 'line-through text-slate-500' : 'text-slate-200' }}"
                                      contenteditable="true" onblur="updateCheckText({{ $i }}, this)">{{ $item['text'] ?? '' }}</span>
                                <button type="button" onclick="removeCheck({{ $i }})" class="opacity-0 group-hover:opacity-100 text-slate-500 hover:text-rose-400 transition-all shrink-0 mt-1">
                                    <i class="ph-bold ph-x text-sm"></i>
                                </button>
                            </div>
                            @endforeach

                            <div class="flex items-center gap-3 pt-2">
                                <div class="w-5 h-5 rounded-[6px] border-2 border-dashed border-slate-600 shrink-0 bg-white/5"></div>
                                <input type="text" id="new-check-input" placeholder="Ketik to-do baru, lalu tekan Enter..." onkeydown="addCheckOnEnter(event)"
                                       class="text-[15px] text-slate-300 bg-transparent border-none outline-none focus:ring-0 p-0 placeholder:text-slate-600 flex-1" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <form id="delete-note-form" action="{{ route('coretan.destroy', $activeNote) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>

            @else
            <div class="flex-1 flex flex-col items-center justify-center text-center p-8 bg-transparent">
                <div class="w-24 h-24 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-6 backdrop-blur-md shadow-lg">
                    <i class="ph-fill ph-note-pencil text-5xl text-slate-400"></i>
                </div>
                <h3 class="text-2xl font-bold text-white font-geist tracking-tight">Ruang Kosong</h3>
                <p class="text-slate-400 mt-2 max-w-sm leading-relaxed">Ga harus bagus, ga harus panjang. Yang penting mulai aja nulis idemu.</p>
                <button onclick="document.getElementById('modal-new-note').classList.remove('hidden')"
                        class="mt-8 px-6 py-3 bg-white text-black font-bold text-sm rounded-xl shadow-md hover:bg-slate-200 transition-all hover:scale-105 active:scale-95 flex items-center gap-2 mx-auto">
                    <i class="ph-bold ph-plus text-lg"></i>
                    Bikin Coretan Pertama
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- Modal Buat Coretan Baru --}}
    <div id="modal-new-note" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('modal-new-note').classList.add('hidden')"></div>
        <div class="relative bg-slate-900/90 rounded-[24px] max-w-md w-full p-6 shadow-2xl border border-white/10 z-10 backdrop-blur-2xl animate-fadeIn">
            <div class="flex items-center justify-between pb-4 border-b border-white/10 mb-5">
                <h3 class="text-lg font-bold text-white font-geist flex items-center gap-2"><i class="ph-fill ph-sparkle text-emerald-400"></i> Bikin coretan baru</h3>
                <button onclick="document.getElementById('modal-new-note').classList.add('hidden')" class="p-1.5 hover:bg-white/10 rounded-full text-slate-400 hover:text-white transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            <form action="{{ route('coretan.store') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Kasih Judul Dulu</label>
                    <input type="text" name="title" required placeholder="Judul ide liar hari ini..."
                           class="w-full px-4 py-3 bg-black/40 border border-white/10 rounded-xl outline-none text-sm text-white focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-600 shadow-inner" />
                </div>
                <div>
                    <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-2">Isi Awal <span class="normal-case font-normal text-slate-500">(Boleh Kosong)</span></label>
                    <textarea name="content" rows="3" placeholder="Mau numpahin apa?"
                              class="w-full px-4 py-3 bg-black/40 border border-white/10 rounded-xl outline-none text-sm text-white focus:border-emerald-500/50 focus:ring-4 focus:ring-emerald-500/10 transition-all placeholder:text-slate-600 resize-none shadow-inner"></textarea>
                </div>
                <div class="pt-3 flex flex-col-reverse sm:flex-row items-center sm:justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-new-note').classList.add('hidden')" class="w-full sm:w-auto justify-center px-5 py-2.5 bg-transparent border border-white/10 text-slate-300 hover:text-white hover:bg-white/5 font-semibold text-sm rounded-xl transition-colors active:scale-95">Batal</button>
                    <button type="submit" class="w-full sm:w-auto justify-center px-6 py-2.5 bg-emerald-500 hover:bg-emerald-400 text-white font-bold text-sm rounded-xl shadow-md transition-all active:scale-95">Yuk, Tulis!</button>
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
        if      (len <  60)  size = 28;
        else if (len < 150)  size = 22;
        else if (len < 400)  size = 18;
        else if (len < 800)  size = 16;
        else                 size = 15;
        currentFontSize = size;
        ta.style.fontSize = size + 'px';
    }

    function changeFontSize(delta) {
        manualOverride = true;
        currentFontSize = Math.min(40, Math.max(12, currentFontSize + delta));
        if (ta) ta.style.fontSize = currentFontSize + 'px';
    }

    function handleContentInput(el) {
        el.style.height = 'auto';
        el.style.height = el.scrollHeight + 'px';
        autoFont(el.value.length);
    }

    if (ta) {
        ta.style.height = 'auto';
        ta.style.height = ta.scrollHeight + 'px';
        autoFont(ta.value.length);
    }

    // ─── AUTO-SAVE (AJAX) ──────────────────────────────────────
    function autoSave() {
        const form = document.getElementById('note-form');
        const btn = document.getElementById('btn-simpan');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';
        
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
            btn.innerHTML = '<i class="ph-bold ph-check text-emerald-400"></i> Tersimpan';
            setTimeout(() => btn.innerHTML = originalText, 2000);
        })
        .catch(err => {
            btn.innerHTML = '<i class="ph-bold ph-x text-rose-500"></i> Gagal';
            setTimeout(() => btn.innerHTML = originalText, 2000);
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
        chip.className = 'hashtag-chip text-[11px] font-bold text-emerald-300 bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 rounded-md cursor-pointer hover:bg-rose-500/20 hover:text-rose-400 hover:border-rose-500/30 transition-colors';
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
            div.className = 'checklist-item flex items-start gap-3 group';
            div.dataset.idx = idx;
            const doneBtn = item.done
                ? `<i class="ph-bold ph-check text-[10px]"></i>`
                : '';
            const doneClass = item.done
                ? 'bg-emerald-500 border-emerald-500 text-white shadow-[0_0_10px_rgba(16,185,129,0.4)]'
                : 'border-slate-600 hover:border-slate-400 bg-white/5';
            const textClass = item.done ? 'line-through text-slate-500' : 'text-slate-200';
            div.innerHTML = `
                <button type="button" onclick="toggleCheck(${idx})"
                        class="w-5 h-5 mt-0.5 rounded-[6px] border-2 shrink-0 flex items-center justify-center transition-all duration-300 ${doneClass}">
                    ${doneBtn}
                </button>
                <span class="text-[15px] flex-1 leading-relaxed outline-none transition-all duration-300 ${textClass}" contenteditable="true" onblur="updateCheckText(${idx}, this)">${item.text}</span>
                <button type="button" onclick="removeCheck(${idx})" class="opacity-0 group-hover:opacity-100 text-slate-500 hover:text-rose-400 transition-all shrink-0 mt-1">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>`;
            container.insertBefore(div, inputRow);
        });
    }
    </script>
    @endpush
</x-app-layout>