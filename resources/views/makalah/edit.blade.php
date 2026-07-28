<x-app-layout>

    <x-slot name="pageTitle">
        {{ $makalah->judul ?: 'Editor Dokumen' }}
    </x-slot>

    <x-slot name="pageSubtitle">
        Ketik aja. Format akademik biar RUANG yang urus.
    </x-slot>

    <x-slot name="headerActions">
        <div class="flex flex-wrap sm:flex-nowrap items-center gap-3 w-full sm:w-auto justify-end">
            <span id="save-status"
                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                ✔ Tersimpan
            </span>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <a href="{{ route('makalah.export.word',$makalah) }}"
                   data-turbo="false"
                   id="btn-export-word"
                   class="flex-1 sm:flex-none min-h-11 flex items-center justify-center gap-2 px-4 rounded-xl bg-stone-900 text-white font-medium hover:bg-stone-800 transition-all active:scale-95 text-sm dark:bg-white dark:text-slate-900">
                    <i class="ph ph-file-doc"></i>
                    Export Word
                </a>

                <a href="{{ route('makalah.export.pdf',$makalah) }}"
                   target="_blank"
                   class="flex-1 sm:flex-none min-h-11 flex items-center justify-center gap-2 px-4 rounded-xl border border-stone-300 font-medium hover:bg-stone-50 transition-all active:scale-95 text-sm bg-white dark:bg-slate-800 dark:border-slate-600 dark:text-white dark:hover:bg-slate-700">
                    <i class="ph ph-file-pdf"></i>
                    Export PDF
                </a>
            </div>

            {{-- Toast Notification F9 --}}
            <div id="word-export-toast"
                 class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50 hidden"
                 role="alert" aria-live="polite">
                <div class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-900 rounded-2xl shadow-xl px-5 py-4 max-w-sm">
                    <span class="text-xl mt-0.5">⌨️</span>
                    <div>
                        <p class="font-semibold text-sm">File Word berhasil diunduh!</p>
                        <p class="text-xs mt-0.5 leading-relaxed text-amber-700">
                            Setelah file terbuka di Microsoft Word, tekan
                            <kbd class="px-1.5 py-0.5 rounded bg-amber-200 font-mono font-bold">Ctrl+A</kbd>
                            lalu
                            <kbd class="px-1.5 py-0.5 rounded bg-amber-200 font-mono font-bold">F9</kbd>
                            untuk memperbarui nomor halaman di Daftar Isi.
                        </p>
                    </div>
                    <button onclick="document.getElementById('word-export-toast').classList.add('hidden')" class="text-amber-400 hover:text-amber-700 ml-1 shrink-0">&times;</button>
                </div>
            </div>

            <script>
                document.getElementById('btn-export-word').addEventListener('click', function() {
                    const toast = document.getElementById('word-export-toast');
                    toast.classList.remove('hidden');
                    // Auto-hide after 10 seconds
                    setTimeout(() => toast.classList.add('hidden'), 10000);
                });
            </script>
        </div>
    </x-slot>

    <div class="bg-stone-100 min-h-screen">

        {{-- No Toolbar inside content anymore, handled by x-app-layout header --}}

        <div class="max-w-4xl mx-auto py-8">

            <div
                class="bg-white text-slate-900 shadow-sm rounded-xl overflow-hidden border border-stone-200 relative">

                {{-- Cover --}}
                @include('makalah.components.cover')

                {{-- Kata Pengantar --}}
                @include('makalah.components.kata-pengantar')

                {{-- Daftar Isi --}}
                @include('makalah.components.daftar-isi')

                {{-- BAB I - VI --}}
                <div id="chapters-wrapper">
                    @include('makalah.components.chapter')
                </div>

                {{-- Daftar Pustaka --}}
                @include('makalah.components.references')

            </div>

        </div>

    </div>



    @stack('styles')
    
    <style>
        .a4-page {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.8;
        }

        .chapter-editor {
            min-height: 180px;
            border: 1px solid #e7e5e4;
            padding: 24px;
            border-radius: 8px;
            background: white;
        }
    </style>

    @stack('scripts')

    {{-- Sticky AI Progress Banner (menggantikan modal) --}}
    <div id="ai-progress-banner" class="fixed top-0 inset-x-0 z-[100] hidden">
        <div class="bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-4 py-3 shadow-lg">
            <div class="max-w-4xl mx-auto flex items-center gap-3 flex-wrap">
                <i class="ph ph-robot text-xl animate-pulse shrink-0"></i>
                <div class="flex-1 min-w-0">
                    <p id="ai-loading-text" class="text-sm font-medium truncate">Menyiapkan ruang...</p>
                    <div class="mt-1.5 w-full bg-white/20 rounded-full h-1.5 overflow-hidden">
                        <div id="ai-progress-bar" class="bg-white h-1.5 rounded-full transition-all duration-500" style="width: 0%"></div>
                    </div>
                </div>
                <span id="ai-progress-pct" class="text-sm font-bold tabular-nums shrink-0">0%</span>
                <div class="flex items-center gap-2 shrink-0">
                    <button id="ai-cancel-btn"
                        class="text-xs px-3 py-1.5 rounded-lg bg-white/20 hover:bg-white/30 transition font-medium">
                        ⛔ Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div id="ai-toast-container" class="fixed bottom-6 right-6 z-[99] flex flex-col gap-2 pointer-events-none"></div>

    {{-- Custom Confirm Modal (mengganti browser confirm) --}}
    <div id="ai-confirm-modal" class="fixed inset-0 z-[200] hidden items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 border border-stone-200">
            <div class="flex items-start gap-3 mb-4">
                <span id="ai-confirm-icon" class="text-2xl shrink-0">⚠️</span>
                <div>
                    <h3 id="ai-confirm-title" class="font-bold text-slate-900 text-base">Konfirmasi</h3>
                    <p id="ai-confirm-body" class="text-sm text-stone-500 mt-1 leading-relaxed"></p>
                </div>
            </div>
            <div id="ai-confirm-buttons" class="flex flex-col gap-2 mt-5"></div>
        </div>
    </div>
    
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar.ql-snow {
            border: none;
            border-bottom: 1px solid #e7e5e4;
            background: #fafaf9;
            border-radius: 8px 8px 0 0;
        }
        .ql-container.ql-snow {
            border: none;
            font-family: inherit;
            font-size: inherit;
        }
        .ql-editor {
            min-height: 150px;
        }
        .ql-editor ul, .ql-editor ol {
            padding-left: 1.5em;
        }
        .ql-editor ul > li {
            margin-bottom: 0.5em;
            text-align: justify;
        }
        .ql-editor ol > li {
            margin-bottom: 0.5em;
            text-align: justify;
        }
        .ql-editor p {
            margin-bottom: 1em;
            text-align: justify;
        }
    </style>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        (function() {
            const csrfMeta = document.querySelector('meta[name="csrf-token"]');
            if (!csrfMeta) return; // Not on the right page
            const csrfToken = csrfMeta.getAttribute('content');
            const saveStatus = document.getElementById('save-status');
            if (!saveStatus) return; // Page elements not found

            function showSaving() {
                saveStatus.innerHTML = '⏳ Menyimpan...';
                saveStatus.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-amber-100 text-amber-700';
            }

            function showSaved() {
                saveStatus.innerHTML = '✔ Tersimpan';
                saveStatus.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700';
            }

            function showError() {
                if(saveStatus) {
                    saveStatus.innerHTML = '✖ Gagal Disimpan';
                    saveStatus.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-red-100 text-red-700';
                }
            }

            // -------------------------------------------------------------
            // 1. Generate Full Makalah AI (Queue & Polling)
            // Dipindah ke atas agar tidak terblokir jika Quill telat loading
            // -------------------------------------------------------------
            const aiBanner  = document.getElementById('ai-progress-banner');
            const aiLoadingText = document.getElementById('ai-loading-text');
            const aiProgressBar = document.getElementById('ai-progress-bar');
            const aiProgressPct = document.getElementById('ai-progress-pct');
            let pollingInterval;

            // ── Toast helper ───────────────────────────────────────────────
            function showToast(msg, type = 'success') {
                const container = document.getElementById('ai-toast-container');
                const toast = document.createElement('div');
                const colors = type === 'success'
                    ? 'bg-violet-600 text-white'
                    : 'bg-red-600 text-white';
                toast.className = `pointer-events-auto flex items-center gap-2 px-4 py-3 rounded-xl shadow-lg text-sm font-medium ${colors} translate-y-2 opacity-0 transition-all duration-300`;
                toast.innerHTML = `<span>${msg}</span>`;
                container.appendChild(toast);
                requestAnimationFrame(() => {
                    toast.classList.remove('translate-y-2', 'opacity-0');
                });
                setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            }

            // ── Banner helpers ─────────────────────────────────────────────
            function openAiBanner(estimasiMenit = null) {
                if (aiBanner) {
                    aiBanner.classList.remove('hidden');
                    document.body.style.paddingTop = aiBanner.offsetHeight + 'px';
                }
                if (estimasiMenit && aiLoadingText) {
                    aiLoadingText.innerText = `Estimasi waktu: ${estimasiMenit}–${estimasiMenit + 2} menit`;
                }
            }
            function closeAiBanner() {
                if (aiBanner) {
                    aiBanner.classList.add('hidden');
                    document.body.style.paddingTop = '';
                }
            }
            function updateProgressBar(pct) {
                if (aiProgressBar) aiProgressBar.style.width = pct + '%';
                if (aiProgressPct) aiProgressPct.textContent = pct + '%';
            }

            // Cancel button in banner
            document.getElementById('ai-cancel-btn')?.addEventListener('click', async () => {
                if (!confirm('Yakin ingin membatalkan proses AI? Konten yang sudah ditulis tidak akan hilang.')) return;
                await fetch("{{ route('api.ai.cancel', $makalah) }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
                });
                clearInterval(pollingInterval);
                closeAiBanner();
                showToast('⛔ Proses AI dibatalkan.', 'error');
            });

            document.addEventListener('click', async (e) => {
                const btnGenerate = e.target.closest('#btn-generate-full');
                if (!btnGenerate) return;

                e.preventDefault();

                try {
                    const titleInput = document.querySelector('textarea[data-field="judul"]');
                    const title = titleInput ? titleInput.value.trim() : '';
                    if (!title) {
                        alert('Silakan isi judul makalah terlebih dahulu!');
                        if (titleInput) titleInput.focus();
                        return;
                    }

            // ── Custom Confirm Modal ───────────────────────────────────────
            const confirmModal  = document.getElementById('ai-confirm-modal');
            const confirmTitle  = document.getElementById('ai-confirm-title');
            const confirmBody   = document.getElementById('ai-confirm-body');
            const confirmBtns   = document.getElementById('ai-confirm-buttons');
            const confirmIcon   = document.getElementById('ai-confirm-icon');

            function showConfirmModal({ icon = '⚠️', title, body, buttons }) {
                return new Promise(resolve => {
                    confirmIcon.textContent  = icon;
                    confirmTitle.textContent = title;
                    confirmBody.innerHTML    = body;
                    confirmBtns.innerHTML    = '';
                    confirmModal.classList.replace('hidden', 'flex');

                    buttons.forEach(({ label, style, value }) => {
                        const btn = document.createElement('button');
                        btn.textContent = label;
                        btn.className   = `w-full min-h-[44px] rounded-xl font-medium text-sm transition active:scale-95 ${style}`;
                        btn.onclick = () => {
                            confirmModal.classList.replace('flex', 'hidden');
                            resolve(value);
                        };
                        confirmBtns.appendChild(btn);
                    });
                });
            }
                    
                    const hasChapters = document.querySelectorAll('.chapter-container').length > 0;
                    let resume = false;

                    const bodyText = hasChapters 
                        ? `AI akan meracik kerangka dan isi makalah untuk:<br><br><strong class="text-slate-900 text-base">"${title}"</strong><br><br><span class="text-red-600 font-medium">⚠️ Perhatian: Struktur bab yang ada di layar saat ini akan dihapus dan diganti sepenuhnya dengan buatan AI.</span><br><br>Estimasi waktu: <strong>5–10 menit</strong>`
                        : `AI akan membuat kerangka dan isi makalah untuk:<br><br><strong class="text-slate-900 text-base">"${title}"</strong><br><br>Estimasi waktu: <strong>5–10 menit</strong>`;

                    const action = await showConfirmModal({
                        icon: '✨',
                        title: 'Generate Makalah dengan AI',
                        body: bodyText,
                        buttons: [
                            { label: '🚀  Ya, racik dengan AI sekarang!', style: 'bg-violet-600 text-white hover:bg-violet-700', value: 'ok' },
                            { label: 'Batal', style: 'text-stone-400 hover:text-stone-600 text-xs mt-2', value: 'cancel' },
                        ]
                    });
                    
                    if (action === 'cancel') return;

                    if (aiLoadingText) aiLoadingText.innerText = 'Menghubungi AI...';
                    
                    const res = await fetch("{{ route('api.ai.generate-full', $makalah) }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ title: title, resume: resume })
                    });
                    
                    const data = await res.json();
                    
                    if (data.success) {
                        openAiBanner(data.estimasi_menit);
                        if (aiLoadingText) aiLoadingText.innerText = 'AI sedang menyusun kerangka makalah...';
                        updateProgressBar(0);
                        startAiPolling();
                    } else {
                        closeAiBanner();
                        showToast('❌ ' + (data.message || 'Gagal memulai proses AI.'), 'error');
                    }
                } catch (err) {
                    console.error('AI Generation Error:', err);
                    closeAiBanner();
                    showToast('❌ Terjadi kesalahan koneksi.', 'error');
                }
            });

            let lastProgressStatus = '';
            let lastPct = 0;
            let pollCount = 0;
            const MAX_POLL_COUNT = 200;

            function startAiPolling() {
                pollCount = 0;
                pollingInterval = setInterval(async () => {
                    pollCount++;

                    if (pollCount > MAX_POLL_COUNT) {
                        clearInterval(pollingInterval);
                        closeAiBanner();
                        showToast('⏱ Proses AI timeout. Refresh halaman untuk melihat hasil.', 'error');
                        return;
                    }

                    try {
                        const res = await fetch("{{ route('api.ai.status', $makalah) }}");
                        const data = await res.json();

                        if (data.ai_progress && aiLoadingText) {
                            aiLoadingText.innerText = data.ai_progress;
                        }

                        // Update progress bar
                        if (data.percentage !== undefined) {
                            updateProgressBar(data.percentage);
                        }

                        // Fetch HTML bab saat progress berubah
                        if (lastProgressStatus !== data.ai_progress &&
                            (data.ai_status === 'processing_chapter' || data.ai_status === 'completed')) {

                            // Toast sub-bab selesai
                            if (lastProgressStatus && data.ai_status === 'processing_chapter') {
                                const match = lastProgressStatus.match(/Menulis '(.+?)'/);
                                if (match) showToast(`✅ "${match[1]}" selesai ditulis`);
                            }

                            lastProgressStatus = data.ai_progress;
                            fetch("{{ route('api.ai.chapters-html', $makalah) }}")
                                .then(r => r.text())
                                .then(html => {
                                    const wrapper = document.getElementById('chapters-wrapper');
                                    if (wrapper) {
                                        wrapper.innerHTML = html;
                                        initEditors();
                                        attachRegenerateButtons();
                                    }
                                });
                        }

                        if (data.ai_status === 'completed') {
                            clearInterval(pollingInterval);
                            updateProgressBar(100);
                            if (aiLoadingText) aiLoadingText.innerText = 'Selesai! ✅';
                            showToast('🎉 Makalah selesai dibuat oleh AI!');
                            setTimeout(() => closeAiBanner(), 3000);
                        } else if (data.ai_status === 'failed') {
                            clearInterval(pollingInterval);
                            closeAiBanner();
                            showToast('❌ Proses AI gagal: ' + data.ai_progress, 'error');
                        } else if (data.ai_status === 'cancelled') {
                            clearInterval(pollingInterval);
                            closeAiBanner();
                        }
                    } catch (e) {
                        console.error('Error polling AI status', e);
                    }
                }, 3000);
            }

            // Auto-start polling if AI is already working in background
            const currentStatus = "{{ $makalah->ai_status }}";
            if (currentStatus === 'queued' || currentStatus === 'processing_outline' || currentStatus === 'processing_chapter') {
                openAiBanner();
                startAiPolling();
            }

            function closeAiModal() { closeAiBanner(); } // backward compat alias

            // ── Regenerate per sub-bab ─────────────────────────────────────
            function attachRegenerateButtons() {
                document.querySelectorAll('.btn-regenerate-sub').forEach(btn => {
                    if (btn.dataset.attached) return;
                    btn.dataset.attached = '1';
                    btn.addEventListener('click', async () => {
                        if (!confirm('Tulis ulang sub-bab ini dengan AI? Isi lama akan digantikan.')) return;
                        const url  = btn.dataset.url;
                        const csrf = btn.dataset.csrf;
                        const subEl = btn.closest('[data-subchapter-id]');
                        const editor = subEl?.querySelector('.chapter-editor');

                        // Tampilkan skeleton
                        btn.disabled = true;
                        btn.textContent = '⏳ Menulis...';
                        if (editor) {
                            editor.innerHTML = '<div class="animate-pulse space-y-3 p-2"><div class="h-3 bg-violet-200 rounded w-full"></div><div class="h-3 bg-violet-200 rounded w-5/6"></div><div class="h-3 bg-violet-200 rounded w-4/6"></div></div>';
                        }

                        try {
                            const res = await fetch(url, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                            });
                            const data = await res.json();
                            if (data.success && editor) {
                                editor.innerHTML = data.content;
                                initEditors();
                                showToast('✅ Sub-bab berhasil ditulis ulang oleh AI!');
                            } else {
                                showToast('❌ ' + (data.message || 'Gagal menulis ulang.'), 'error');
                                if (editor) editor.innerHTML = '';
                            }
                        } catch(e) {
                            showToast('❌ Koneksi bermasalah.', 'error');
                        } finally {
                            btn.disabled = false;
                            btn.textContent = '🔄 Tulis Ulang';
                        }
                    });
                });
            }
            attachRegenerateButtons();

            const toolbarOptions = [
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'script': 'sub'}, { 'script': 'super' }],
                ['clean']
            ];

            // -------------------------------------------------------------
            // 2. Auto-save inputs (Cover, etc)
            // -------------------------------------------------------------
            const autoSaveInputs = document.querySelectorAll('.auto-save');
            let inputTimeout;
            autoSaveInputs.forEach(input => {
                const eventName = input.tagName === 'SELECT' ? 'change' : 'input';
                input.addEventListener(eventName, () => {
                    showSaving();
                    clearTimeout(inputTimeout);
                    inputTimeout = setTimeout(() => saveIdentity(), 1000);
                });
            });

            async function saveIdentity() {
                const data = {};
                autoSaveInputs.forEach(input => {
                    data[input.getAttribute('data-field')] = input.value;
                });

                try {
                    const res = await fetch("{{ route('makalah.update', $makalah) }}", {
                        method: 'PATCH',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify(data)
                    });
                    if(res.ok) showSaved(); else showError();
                } catch(e) { showError(); }
            }

            // 1.5 Auto-save chapter titles
            const autoSaveChapterTitles = document.querySelectorAll('.auto-save-chapter-title');
            let chapterTitleTimeout;
            autoSaveChapterTitles.forEach(input => {
                input.addEventListener('input', () => {
                    showSaving();
                    clearTimeout(chapterTitleTimeout);
                    chapterTitleTimeout = setTimeout(async () => {
                        const url = input.getAttribute('data-url');
                        const field = input.getAttribute('data-field');
                        const method = url.includes('subchapters') ? 'PUT' : 'PATCH';
                        try {
                            const res = await fetch(url, {
                                method: method,
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                body: JSON.stringify({ [field]: input.value })
                            });
                            if(res.ok) showSaved(); else showError();
                        } catch(e) { showError(); }
                    }, 1000);
                });
            });

            // 2. Preface Editor (Kata Pengantar)
            const prefaceEl = document.getElementById('preface-editor');
            if (prefaceEl) {
                const prefaceQuill = new Quill(prefaceEl, { theme: 'snow', modules: { toolbar: toolbarOptions }, placeholder: 'Tulis kata pengantar...' });
                let prefaceTimeout;
                prefaceQuill.on('text-change', function() {
                    showSaving();
                    clearTimeout(prefaceTimeout);
                    prefaceTimeout = setTimeout(async () => {
                        try {
                            const res = await fetch("{{ route('makalah.update', $makalah) }}", {
                                method: 'PATCH',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                body: JSON.stringify({ kata_pengantar: prefaceQuill.root.innerHTML })
                            });
                            if(res.ok) showSaved(); else showError();
                        } catch(e) { showError(); }
                    }, 1000);
                });
            }
            // 3. Chapter Editors (Bab I - VI)
            window.initEditors = function() {
                const chapterEditors = document.querySelectorAll('.chapter-editor');
                chapterEditors.forEach(el => {
                    // Prevent re-initialization on Turbo navigations
                    if (el.classList.contains('ql-container')) return;

                const quill = new Quill(el, { theme: 'snow', modules: { toolbar: toolbarOptions }, placeholder: 'Mulai menulis konten bab ini...' });
                const Delta = Quill.import('delta');
                let chapterTimeout;
                let aiTimeout;
                let aiSuggestionRange = null;
                let isAiInserting = false;

                // Handle Tab for AI Suggestion
                quill.keyboard.addBinding({ key: 'Tab' }, function(range, context) {
                    if (aiSuggestionRange) {
                        isAiInserting = true;
                        quill.formatText(aiSuggestionRange.index, aiSuggestionRange.length, 'color', false);
                        quill.setSelection(aiSuggestionRange.index + aiSuggestionRange.length);
                        aiSuggestionRange = null;
                        isAiInserting = false;
                        
                        // Trigger save
                        quill.emitter.emit('text-change', new Delta(), new Delta(), 'user');
                        return false;
                    }
                    return true;
                });

                quill.on('text-change', function(delta, oldDelta, source) {
                    if (isAiInserting) return;

                    // Clear previous suggestion if user types
                    if (source === 'user' && aiSuggestionRange) {
                        isAiInserting = true;
                        quill.deleteText(aiSuggestionRange.index, aiSuggestionRange.length);
                        aiSuggestionRange = null;
                        isAiInserting = false;
                    }

                    showSaving();
                    clearTimeout(chapterTimeout);
                    chapterTimeout = setTimeout(async () => {
                        try {
                            const res = await fetch("{{ route('makalah.contents.update', $makalah) }}", {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                body: JSON.stringify({ 
                                    subchapter_id: el.getAttribute('data-subchapter-id'), 
                                    content: quill.root.innerHTML 
                                })
                            });
                            if(res.ok) showSaved(); else showError();
                        } catch(e) { showError(); }
                    }, 1000);

                    // AI Autopilot Trigger
                    if (source === 'user') {
                        clearTimeout(aiTimeout);
                        aiTimeout = setTimeout(async () => {
                            const text = quill.getText();
                            if (text.trim().length < 20) return;
                            
                            try {
                                const res = await fetch("/api/ai/autocomplete", {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                    body: JSON.stringify({ 
                                        context: text.substring(0, Math.max(0, text.length - 300)),
                                        current_text: text.substring(Math.max(0, text.length - 300))
                                    })
                                });
                                const data = await res.json();
                                if (data.success && data.suggestion) {
                                    const cursorPos = quill.getSelection() ? quill.getSelection().index : quill.getLength() - 1;
                                    
                                    isAiInserting = true;
                                    // Add space if needed
                                    const prefix = text.endsWith(' ') || text.endsWith('\n') ? '' : ' ';
                                    const suggestionText = prefix + data.suggestion.trim();
                                    
                                    quill.insertText(cursorPos, suggestionText, { color: '#9ca3af' });
                                    aiSuggestionRange = { index: cursorPos, length: suggestionText.length };
                                    quill.setSelection(cursorPos);
                                    isAiInserting = false;
                                }
                            } catch(e) {}
                        }, 2000);
                    }
                });
            }); // Close forEach correctly
            }; // Close initEditors
            window.initEditors();

        })();
    </script>

</x-app-layout>