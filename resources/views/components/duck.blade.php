<div id="duck-mascot"
     data-turbo-permanent
     x-data="duckSystem()"
     @click.outside="closeChat()"   
     class="fixed top-1 md:-top-4 right-1 md:right-5 z-[100] pointer-events-auto select-none font-sans flex flex-col items-end"
     x-cloak>

    <div class="relative w-36 h-36 md:w-40 md:h-40 cursor-pointer group z-10" 
     @click="handleDuckClick()" 
     :class="{ 'animate-bounce': isWalking }">
    
    <!-- === CSS ANIMATION (Tanpa JS) === -->
    <style>
        /* Animasi napas kembang-kempis santai */
        @keyframes idle-breathe {
            0%, 100% { transform: scaleY(1); }
            50% { transform: scaleY(0.97) translateY(2px); }
        }
        /* Animasi tamborin goyang tipis terus-menerus */
        @keyframes idle-jingle {
            0%, 100% { transform: rotate(-18deg) translate(23px, 80px) rotate(0deg) translate(-23px, -80px); }
            10% { transform: rotate(-18deg) translate(23px, 80px) rotate(-10deg) translate(-23px, -80px); }
            20% { transform: rotate(-18deg) translate(23px, 80px) rotate(10deg) translate(-23px, -80px); }
            30% { transform: rotate(-18deg) translate(23px, 80px) rotate(0deg) translate(-23px, -80px); }
        }
        /* Animasi tamborin pas di-hover */
        @keyframes aggro-jingle {
            0%, 100% { transform: rotate(-18deg) translate(23px, 80px) rotate(0deg) translate(-23px, -80px); }
            25% { transform: rotate(-18deg) translate(23px, 80px) rotate(-12deg) translate(-23px, -80px); }
            75% { transform: rotate(-18deg) translate(23px, 80px) rotate(12deg) translate(-23px, -80px); }
        }

        .duck-body { 
            animation: idle-breathe 3s ease-in-out infinite; 
            transform-origin: center bottom; 
        }
        .tambourine-group { 
            animation: idle-jingle 4s ease-in-out infinite; 
        }
        .group:hover .tambourine-group { 
            animation: aggro-jingle 0.25s ease-in-out infinite; 
        }
    </style>

    <!-- === HOVER BUBBLE (Subtle Frosted Pill) === -->
    <div class="absolute top-7 -left-12 opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none z-20">
        <div class="relative bg-black/80 text-white font-bold px-3 py-1 text-xs rounded-full border border-white/20 backdrop-blur-md shadow-lg tracking-wide font-mono">
            wotcher.
        </div>
    </div>

    <!-- === PNG DUCK === -->
    <div class="relative w-full h-full">
        <!-- Gambar Bebek -->
        <img src="{{ asset('images/bebek.png') }}" 
             class="w-full h-full object-contain drop-shadow-xl transition-transform duration-300 group-hover:-translate-y-1.5 group-active:scale-95" 
             :class="['dengerin', 'konser', 'asbun'].includes(mood) ? 'duck-shaking' : 'duck-body'"
             alt="Bebek">

        <!-- === MOODS (Muncul di atas gambar) === -->
        <div class="absolute -top-4 right-0 pointer-events-none">
            
            <!-- NGANTUK (zzz) -->
            <div x-show="mood === 'ngantuk'" class="animate-pulse text-slate-400 font-bold">
                <span class="text-xl absolute right-6 top-2">z</span>
                <span class="text-sm absolute right-2 -top-2">z</span>
            </div>
            
            <!-- DENGERIN (music notes) -->
            <div x-show="mood === 'dengerin'" class="animate-bounce text-neutral-900 font-bold">
                <span class="text-xl absolute right-6 top-2"><i class="ph ph-music-notes text-[1.1em] align-middle"></i></span>
                <span class="text-sm absolute right-2 -top-2"><i class="ph ph-music-notes-simple text-[1.1em] align-middle"></i></span>
            </div>

            <!-- BENGONG (...) -->
            <div x-show="mood === 'bengong'" class="animate-pulse text-slate-400 font-black text-2xl absolute right-4 top-0">...</div>

            <!-- NYINYIR (!#@) -->
            <div x-show="mood === 'nyinyir'" class="animate-bounce text-rose-500 font-black text-xl absolute right-4 top-0">#@!</div>

            <!-- MALES (Sigh drop) -->
            <div x-show="mood === 'males'" class="absolute right-4 top-0">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M 5 5 Q 10 0 15 10" stroke="#94a3b8" stroke-width="2" fill="none" class="opacity-60"/>
                    <circle cx="15" cy="12" r="2" fill="#38bdf8" class="animate-pulse"/>
                </svg>
            </div>

            <!-- PEDE (Sparkles) -->
            <div x-show="mood === 'pede'" class="animate-pulse text-amber-400 text-xl absolute right-4 top-0"><i class="ph ph-sparkle text-[1.1em] align-middle"></i></div>

            <!-- ASBUN (Talkative bubble) -->
            <div x-show="mood === 'asbun'" class="animate-bounce text-sky-400 text-xl absolute right-4 top-0"><i class="ph ph-chat-circle text-[1.1em] align-middle"></i>️</div>

            <!-- LAPER (Chicken leg / food) -->
            <div x-show="mood === 'laper'" class="animate-pulse text-red-400 text-xl absolute right-4 top-0"><i class="ph ph-bone text-[1.1em] align-middle"></i></div>

            <!-- KONSER (Mic) -->
            <div x-show="mood === 'konser'" class="animate-bounce text-purple-500">
                <span class="text-xl absolute right-6 top-2"><i class="ph ph-microphone text-[1.1em] align-middle"></i></span>
                <span class="text-sm absolute right-2 -top-2"><i class="ph ph-music-notes text-[1.1em] align-middle"></i></span>
            </div>

            <!-- OVERTHINKING (Swirl) -->
            <div x-show="mood === 'overthinking'" class="animate-spin text-slate-500 text-xl absolute right-4 top-0" style="transform-origin: center;"><i class="ph ph-spiral text-[1.1em] align-middle"></i></div>
        </div>
    </div>

    <!-- Speech Bubble (Now below Duck) -->
    <div x-show="bubbleVisible" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="absolute top-[50px] right-[120px] mt-2 bg-black/95 text-white px-4 py-2.5 rounded-2xl rounded-tr-none shadow-xl border border-white/15 min-w-[140px] max-w-[260px] text-sm font-medium cursor-pointer z-20 backdrop-blur-md"
         @click="openChat()">
        <p x-text="currentMessage" class="leading-relaxed"></p>
    </div>

    <!-- Mini Chat Popover (Modern iOS 17/18 iMessage Experience) -->
    <div x-show="chatVisible"
         @click.stop
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
         id="duck-chat-popover"
         class="fixed top-14 right-2 w-[calc(100vw-16px)] max-w-[335px] md:w-[335px] md:absolute md:top-28 md:right-0 bg-black rounded-[28px] shadow-[0_25px_60px_rgba(0,0,0,0.8)] border border-white/[0.12] overflow-hidden flex flex-col z-[200] backdrop-blur-3xl">
        
        <!-- iOS 17 Authentic Top Navigation Header -->
        <div class="px-3 pt-3 pb-2.5 bg-[#161618]/95 border-b border-white/[0.08] flex items-center justify-between relative shrink-0">
            <!-- iOS Back Button -->
            <button @click="closeChat()" class="flex items-center text-[#007aff] hover:opacity-80 transition-opacity -ml-1 text-[14px] font-normal" title="Messages">
                <i class="ph-bold ph-caret-left text-lg"></i>
                <span class="text-xs font-medium -ml-0.5">Messages</span>
            </button>

            <!-- Centered Contact Info -->
            <div class="flex flex-col items-center justify-center -ml-2 cursor-default">
                <div class="w-9 h-9 rounded-full bg-[#26252A] border border-white/15 flex items-center justify-center overflow-hidden shadow-inner mb-0.5">
                    <img src="{{ asset('images/bebek.png') }}" alt="Duki" class="w-7 h-7 object-contain">
                </div>
                <div class="flex items-center gap-0.5">
                    <span class="text-[11px] font-semibold text-white tracking-tight font-sans">Duki</span>
                    <i class="ph-bold ph-caret-right text-[8px] text-[#86868b]"></i>
                </div>
            </div>

            <!-- Close / FaceTime Button -->
            <button @click="closeChat()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white flex items-center justify-center transition-colors" title="Close">
                <i class="ph ph-x text-xs"></i>
            </button>
        </div>

        <!-- iOS iMessage Chat History (OLED Black) -->
        <div class="p-3.5 max-h-52 md:max-h-60 min-h-[140px] overflow-y-auto space-y-2.5 bg-black" id="duck-chat-history">
            {{-- Centered Timestamp Pill --}}
            <div class="text-center my-1">
                <span class="text-[10px] text-[#86868b] font-medium font-sans">Today {{ date('g:i A') }}</span>
            </div>

            <template x-for="msg in chatHistory">
                <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <div class="px-3.5 py-2 text-[14.5px] leading-[1.35] max-w-[82%] shadow-sm"
                         style="font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', sans-serif; word-wrap: break-word;"
                         :class="msg.role === 'user' 
                                ? 'bg-gradient-to-b from-[#1a8cff] to-[#0071e3] text-white rounded-[20px] rounded-br-[6px] font-normal tracking-[-0.01em]' 
                                : 'bg-[#26252a] text-white rounded-[20px] rounded-bl-[6px] border border-white/5 font-normal tracking-[-0.01em]'">
                        <span x-text="msg.content"></span>
                    </div>
                    <!-- Read Receipt -->
                    <template x-if="msg.role === 'user' && msg.status && isLastUserMessage(msg)">
                        <div class="flex justify-end w-full mt-1 pr-1.5">
                            <span class="text-[10px] text-[#86868b] font-sans font-medium" x-text="msg.status === 'seen' ? 'Read' : 'Delivered'"></span>
                        </div>
                    </template>
                </div>
            </template>

            <!-- Typing Bubble -->
            <div x-show="isTyping" class="flex items-center gap-1 bg-[#26252a] px-3.5 py-2.5 rounded-[20px] rounded-bl-[6px] w-fit border border-white/5">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.15s;"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay: 0.3s;"></span>
            </div>
        </div>

        <!-- iOS 17 Authentic Redesigned Input Bar -->
        <div class="border-t border-white/10 px-2.5 py-2 flex items-center gap-2 bg-black font-sans shrink-0">
            <!-- iOS 17 Plus Button -->
            <button type="button" class="w-8 h-8 rounded-full bg-[#26252a] hover:bg-[#343338] text-[#8e8e93] hover:text-white flex items-center justify-center transition-all active:scale-95 shrink-0" title="Apps">
                <i class="ph-bold ph-plus text-sm"></i>
            </button>

            <!-- Pill Input Box -->
            <div class="flex-1 relative flex items-center">
                <input type="text" x-model="chatInput" @input="delaySend()" @keydown.enter.prevent.stop="sendMessage()" @focus="if (window.innerWidth < 768) { const chat = document.getElementById('duck-chat-popover'); if (chat) chat.style.top = '48px'; } const _y = window.scrollY; setTimeout(() => window.scrollTo(0, _y), 50)" placeholder="iMessage" class="w-full bg-[#1c1c1e] text-[14.5px] rounded-full pl-3.5 pr-2 py-1.5 border border-[#38383a] focus:border-[#007aff] focus:ring-1 focus:ring-[#007aff] outline-none text-white placeholder-[#636366] transition-colors" autocomplete="off" inputmode="text" style="font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Text', sans-serif;">
            </div>

            <!-- Dynamic Send / Mic Button -->
            <template x-if="chatInput.trim()">
                <button @click.prevent.stop="sendMessage()" class="w-8 h-8 rounded-full bg-[#007aff] hover:bg-[#0062cc] flex items-center justify-center text-white transition-all active:scale-90 shrink-0 shadow-sm" title="Send">
                    <i class="ph-bold ph-arrow-up text-[15px]"></i>
                </button>
            </template>
            <template x-if="!chatInput.trim()">
                <button type="button" class="w-8 h-8 rounded-full text-[#8e8e93] hover:text-white flex items-center justify-center shrink-0" title="Dictation">
                    <i class="ph ph-microphone text-lg"></i>
                </button>
            </template>
        </div>
    </div>
</div>

<style>
    /* Animasi napas kembang-kempis santai */
    @keyframes idle-breathe {
        0%, 100% { transform: scaleY(1); }
        50% { transform: scaleY(0.97) translateY(2px); }
    }
    /* Animasi goyang tamborin (seluruh badan getar dikit) */
    @keyframes duck-shake {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(-4deg) translateY(-2px); }
        75% { transform: rotate(4deg) translateY(-2px); }
    }
    @keyframes blink-eye {
        0%, 96%, 98% { transform: scaleY(1); }
        97% { transform: scaleY(0.1); }
    }
    .animate-blink {
        animation: blink-eye 4s infinite;
    }
    @keyframes duck-waddle {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        25% { transform: translateY(-2px) rotate(-3deg); }
        75% { transform: translateY(-2px) rotate(3deg); }
    }
    .duck-walking {
        animation: duck-waddle 0.5s infinite;
    }
    
    /* Class tambahan */
    .duck-body { 
        animation: idle-breathe 3s ease-in-out infinite; 
        transform-origin: center bottom; 
    }
    /* Kalau lagi dengerin atau konser, dia goyang cepet */
    .duck-shaking {
        animation: duck-shake 0.3s ease-in-out infinite;
        transform-origin: bottom center;
    }
</style>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('duckSystem', () => ({
            bubbleVisible: false,
            chatVisible: false,
            currentMessage: '',
            chatInput: '',
            isTyping: false,
            mood: 'santai', // santai, ngantuk, dengerin
            isWalking: false,
            bubbleTimer: null,
            idleTimer: null,
            randomTimer: null,
            chatHistory: [],
            msgCounter: 0,
            clickCount: 0,
            clickResetTimer: null,

            init() {
                const currentUserId = '{{ Auth::id() ?? "guest" }}';
                const storedUserId = sessionStorage.getItem('duck_auth_user');

                // Jika user logout / berganti akun / sesi baru, reset riwayat chat duck
                if (storedUserId && storedUserId !== currentUserId) {
                    sessionStorage.removeItem('duck_chat_history');
                    sessionStorage.removeItem('duck_welcomed');
                    this.chatHistory = [];
                }
                sessionStorage.setItem('duck_auth_user', currentUserId);

                // Restore chat history from sessionStorage
                const saved = sessionStorage.getItem('duck_chat_history');
                if (saved) {
                    try {
                        this.chatHistory = JSON.parse(saved);
                    } catch (e) {
                        this.chatHistory = [];
                    }
                } else {
                    this.chatHistory = [];
                }

                // Auto-save history when modified
                this.$watch('chatHistory', (val) => {
                    try {
                        sessionStorage.setItem('duck_chat_history', JSON.stringify(val.slice(-50)));
                    } catch (e) {}
                });

                // Initial welcome after 5s if not welcomed yet in this session
                if (!sessionStorage.getItem('duck_welcomed')) {
                    setTimeout(() => {
                        this.triggerEvent('dashboard');
                        sessionStorage.setItem('duck_welcomed', 'true');
                    }, 5000);
                }
                
                // Track Idle (3 minutes)
                this.resetIdle();
                window.addEventListener('mousemove', () => this.resetIdle());
                window.addEventListener('keydown', () => this.resetIdle());

                // Page Context / Turbo load reactivity
                const checkPageContext = () => {
                    if (window.location.pathname.includes('/dengerin')) {
                        if (this.mood !== 'ngantuk') this.mood = 'dengerin';
                    }
                };
                checkPageContext();
                document.addEventListener('turbo:load', checkPageContext);

                // Listen to Pomodoro finishes globally
                window.addEventListener('pomodoro-finished', () => {
                    this.mood = 'pede';
                    const congrats = [
                        'tumben lu produktif',
                        'udah kelar noh 1 sesi, ngopi dulu',
                        'mantap juga lu, istirahat dulu napa',
                        'kelar juga, jangan dipaksa ntar tipes'
                    ];
                    this.say(congrats[Math.floor(Math.random() * congrats.length)]);
                });

                // Start random event scheduler
                this.scheduleRandomEvent();
            },

            getMoodForContext() {
                // 1. Cek halaman saat ini
                const path = window.location.pathname;
                if (path.includes('/dengerin')) {
                    return Math.random() < 0.6 ? 'dengerin' : 'konser';
                }
                if (path.includes('/papers') || path.includes('/notes')) {
                    const studyMoods = ['males', 'overthinking', 'bengong', 'nyinyir', 'santai'];
                    return studyMoods[Math.floor(Math.random() * studyMoods.length)];
                }

                // 2. Cek jam saat ini
                const hour = new Date().getHours();
                if (hour >= 0 && hour < 5) {
                    // Dini hari: overthinking, ngantuk, bengong
                    const midnightMoods = ['overthinking', 'ngantuk', 'bengong', 'males'];
                    return midnightMoods[Math.floor(Math.random() * midnightMoods.length)];
                } else if (hour >= 11 && hour <= 13) {
                    // Jam makan siang: laper, santai, asbun
                    const lunchMoods = ['laper', 'santai', 'asbun'];
                    return lunchMoods[Math.floor(Math.random() * lunchMoods.length)];
                }

                // 3. Fallback variasi mood acak
                const defaultMoods = ['santai', 'bengong', 'nyinyir', 'males', 'pede', 'asbun', 'laper', 'konser', 'overthinking'];
                return defaultMoods[Math.floor(Math.random() * defaultMoods.length)];
            },

            scheduleRandomEvent() {
                clearTimeout(this.randomTimer);
                // Random time between 2 to 8 minutes (120,000 to 480,000 ms)
                const nextTime = Math.floor(Math.random() * (480000 - 120000 + 1)) + 120000;
                
                this.randomTimer = setTimeout(() => {
                    // Hanya nyeletuk random kalau lagi aktif (tidak ngantuk) dan chat tertutup
                    if (this.mood !== 'ngantuk' && !this.chatVisible) {
                        this.triggerEvent('random');
                    }
                    this.scheduleRandomEvent();
                }, nextTime);
            },

            resetIdle() {
                clearTimeout(this.idleTimer);
                this.mood = 'santai';
                this.idleTimer = setTimeout(() => {
                    this.mood = 'ngantuk';
                    this.triggerEvent('idle');
                }, 180000); // 3 minutes
            },

            handleDuckClick() {
                this.clickCount++;
                clearTimeout(this.clickResetTimer);
                this.clickResetTimer = setTimeout(() => { this.clickCount = 0; }, 1000);

                // Easter egg: spam click 3x
                if (this.clickCount >= 3) {
                    this.clickCount = 0;
                    this.mood = 'nyinyir';
                    const pokeResponses = [
                        'ngapain klik-klik mulu jir, geli',
                        'santai napa tangannya, iseng amat',
                        'jangan dipencet mulu buset',
                        'rusak ntar layar lu',
                        'apasi colek-colek mulu'
                    ];
                    this.say(pokeResponses[Math.floor(Math.random() * pokeResponses.length)]);
                    return;
                }

                this.toggleChat();
            },

            async triggerEvent(eventName) {
                if (this.chatVisible) return; // Don't interrupt if user is chatting
                
                try {
                    const res = await fetch('/duck/event', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ event: eventName })
                    });
                    const data = await res.json();
                    if (data.success && data.content) {
                        this.say(data.content);
                    }
                } catch (e) {
                    console.error('Duck event error', e);
                }
            },

            say(message) {
                let messages = message.split('||').map(m => m.trim()).filter(m => m);
                this.currentMessage = messages[0];
                this.bubbleVisible = true;
                this.chatVisible = false;
                
                // Context-aware mood everytime Duck speaks (except if specifically ngantuk or dengerin)
                if (this.mood !== 'ngantuk' && this.mood !== 'dengerin') {
                    this.mood = this.getMoodForContext();
                }
                
                // Add to chat history automatically just in case user opens it
                messages.forEach(msg => {
                    if (this.chatHistory.length === 0 || this.chatHistory[this.chatHistory.length - 1].content !== msg) {
                        this.chatHistory.push({ role: 'duck', content: msg });
                    }
                });
                this.saveHistory();

                clearTimeout(this.bubbleTimer);
                
                const processNextMessage = (index) => {
                    this.bubbleTimer = setTimeout(() => {
                        this.bubbleVisible = false;
                        if (index < messages.length) {
                            setTimeout(() => {
                                this.currentMessage = messages[index];
                                this.bubbleVisible = true;
                                processNextMessage(index + 1);
                            }, 500); // jeda antar bubble pop up
                        } else {
                            if (this.mood !== 'ngantuk' && this.mood !== 'dengerin') this.mood = 'santai';
                        }
                    }, Math.max(4000, this.currentMessage.length * 100));
                };
                
                processNextMessage(1);
            },

            saveHistory() {
                try {
                    sessionStorage.setItem('duck_chat_history', JSON.stringify(this.chatHistory.slice(-50)));
                } catch (e) {}
            },

            toggleChat() {
                if (this.chatVisible) {
                    this.closeChat();
                } else {
                    this.openChat();
                }
            },

            openChat() {
                this.chatVisible = true;
                this.bubbleVisible = false;
                // Reposition chat below duck on mobile (dinaikkan agar tidak nabrak keyboard)
                if (window.innerWidth < 768) {
                    this.$nextTick(() => {
                        const duck = document.getElementById('duck-mascot');
                        const chat = document.getElementById('duck-chat-popover');
                        if (duck && chat) {
                            const rect = duck.getBoundingClientRect();
                            const targetTop = Math.max(50, Math.min(rect.bottom - 85, window.innerHeight - 340));
                            chat.style.top = targetTop + 'px';
                            chat.style.right = '8px';
                        }
                    });
                }
                setTimeout(() => {
                    this.scrollToBottom();
                    const input = this.$el.querySelector('input');
                    // Jangan auto-focus di mobile (menyebabkan keyboard pop up & layar lompat/naik)
                    if(input && window.innerWidth >= 768) input.focus({ preventScroll: true });
                }, 100);
            },

            closeChat() {
                this.chatVisible = false;
            },

            async sendMessage() {
                if (!this.chatInput.trim()) return;
                
                const userMsg = this.chatInput.trim();
                const msgId = ++this.msgCounter;
                const userMsgObj = { role: 'user', content: userMsg, status: 'delivered', id: msgId };
                this.chatHistory.push(userMsgObj);
                this.saveHistory();
                this.chatInput = '';
                this.isTyping = false; // Jangan tampilkan typing dulu
                this.scrollToBottom();

                // Tunggu 0.8 detik → Seen, lalu baru tampilkan isTyping
                await new Promise(resolve => setTimeout(resolve, 800));
                const mSeen = this.chatHistory.find(x => x.id === msgId);
                if (mSeen) mSeen.status = 'seen';
                this.isTyping = true;
                this.scrollToBottom();

                // Siapkan seluruh riwayat obrolan yang ada di layar (maksimal 50 pesan)
                const onScreenHistory = this.chatHistory.slice(-50).map(m => ({
                    role: m.role,
                    content: m.content
                }));

                try {
                    const res = await fetch('/duck/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ 
                            message: userMsg,
                            history: onScreenHistory
                        })
                    });
                    const data = await res.json();
                    
                    if (data.success) {
                        let messages = data.content.split('||').map(m => m.trim()).filter(m => m);
                        this.chatHistory.push({ role: 'duck', content: messages[0] });
                        this.saveHistory();
                        
                        if (messages.length > 1) {
                            let delay = 800;
                            for (let i = 1; i < messages.length; i++) {
                                setTimeout(() => {
                                    this.isTyping = true;
                                    this.scrollToBottom();
                                    setTimeout(() => {
                                        this.chatHistory.push({ role: 'duck', content: messages[i] });
                                        this.saveHistory();
                                        this.isTyping = false;
                                        this.scrollToBottom();
                                    }, 1000);
                                }, delay);
                                delay += 1800;
                            }
                        }
                    } else {
                        this.chatHistory.push({ role: 'duck', content: 'males jawab.' });
                        this.saveHistory();
                    }
                } catch (e) {
                    this.chatHistory.push({ role: 'duck', content: 'ngantuk gue.' });
                    this.saveHistory();
                } finally {
                    this.isTyping = false;
                    this.scrollToBottom();
                    setTimeout(() => {
                        const input = this.$el.querySelector('input');
                        if (input) input.focus({ preventScroll: true });
                    }, 50);
                }
            },

            scrollToBottom() {
                setTimeout(() => {
                    const container = document.getElementById('duck-chat-history');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 50);
            },

            isLastUserMessage(msg) {
                const userMsgs = this.chatHistory.filter(m => m.role === 'user');
                return userMsgs.length > 0 && userMsgs[userMsgs.length - 1].id === msg.id;
            }
        }));
    });
</script>