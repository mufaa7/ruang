<div id="duck-mascot"
     x-data="duckSystem()"
     class="fixed -top-8 right-0 z-[100] pointer-events-auto select-none font-sans flex flex-col items-end"
     x-cloak
     @click.away="closeChat()">

    <div class="relative w-40 h-40 cursor-pointer group z-10" 
     @click="toggleChat()" 
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
        /* Animasi tamborin pas di-hover (goyang brutal) */
        @keyframes aggro-jingle {
            0%, 100% { transform: rotate(-18deg) translate(23px, 80px) rotate(0deg) translate(-23px, -80px); }
            25% { transform: rotate(-18deg) translate(23px, 80px) rotate(-20deg) translate(-23px, -80px); }
            75% { transform: rotate(-18deg) translate(23px, 80px) rotate(20deg) translate(-23px, -80px); }
        }

        .duck-body { 
            animation: idle-breathe 3s ease-in-out infinite; 
            transform-origin: center bottom; 
        }
        .tambourine-group { 
            animation: idle-jingle 4s ease-in-out infinite; 
        }
        .group:hover .tambourine-group { 
            animation: aggro-jingle 0.15s ease-in-out infinite; 
        }
    </style>

    <!-- === COMIC BUBBLE (Muncul saat hover pake Tailwind) === -->
    <div class="absolute top-10 right-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-20 rotate-6 group-hover:animate-pulse">
        <div class="bg-amber-400 text-stone-900 font-black px-3 py-1 text-xs rounded-lg border-2 border-stone-900 shadow-[3px_3px_0px_0px_rgba(28,25,23,1)]">
            WOTCHER!
            <!-- Tail balon kata -->
            <div class="absolute -bottom-2 left-4 w-0 h-0 border-l-[6px] border-l-transparent border-t-[8px] border-t-stone-900 border-r-[6px] border-r-transparent"></div>
        </div>
    </div>

    <!-- === PNG DUCK === -->
    <div class="relative w-full h-full">
        <!-- Gambar Bebek -->
        <img src="{{ asset('images/bebek.png') }}" 
             class="w-full h-full object-contain drop-shadow-xl transition-transform duration-300 group-hover:-translate-y-2 group-active:scale-95" 
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
            <div x-show="mood === 'dengerin'" class="animate-bounce text-indigo-500 font-bold">
                <span class="text-xl absolute right-6 top-2">♪</span>
                <span class="text-sm absolute right-2 -top-2">♫</span>
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
            <div x-show="mood === 'pede'" class="animate-pulse text-amber-400 text-xl absolute right-4 top-0">✨</div>

            <!-- ASBUN (Talkative bubble) -->
            <div x-show="mood === 'asbun'" class="animate-bounce text-sky-400 text-xl absolute right-4 top-0">🗯️</div>

            <!-- LAPER (Chicken leg / food) -->
            <div x-show="mood === 'laper'" class="animate-pulse text-red-400 text-xl absolute right-4 top-0">🍗</div>

            <!-- KONSER (Mic) -->
            <div x-show="mood === 'konser'" class="animate-bounce text-purple-500">
                <span class="text-xl absolute right-6 top-2">🎤</span>
                <span class="text-sm absolute right-2 -top-2">♪</span>
            </div>

            <!-- OVERTHINKING (Swirl) -->
            <div x-show="mood === 'overthinking'" class="animate-spin text-slate-500 text-xl absolute right-4 top-0" style="transform-origin: center;">🌀</div>
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
         class="absolute top-[65px] right-[145px] mt-2 bg-white dark:bg-slate-800 text-slate-800 dark:text-white px-5 py-3 rounded-2xl rounded-tr-none shadow-lg border border-slate-200 dark:border-slate-700 min-w-[140px] max-w-[260px] text-base font-medium shadow-[0_4px_20px_rgba(0,0,0,0.08)] cursor-pointer z-20"
         @click="openChat()">
        <p x-text="currentMessage" class="leading-relaxed"></p>
    </div>

    <!-- Mini Chat Popover (Now below Duck) -->
    <div x-show="chatVisible"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
         class="absolute top-44 right-0 mt-2 w-72 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col shadow-[0_8px_30px_rgba(0,0,0,0.12)] z-20">
        
        <!-- Chat History -->
        <div class="p-3 max-h-48 overflow-y-auto space-y-3 bg-slate-50/50 dark:bg-slate-900/50" id="duck-chat-history">
            <template x-for="msg in chatHistory">
                <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <span class="text-[10px] font-medium mb-0.5 opacity-50 px-1" x-text="msg.role === 'user' ? 'Lu' : 'Duck'"></span>
                    <div class="px-3 py-1.5 rounded-2xl text-[13px] inline-block"
                         :class="msg.role === 'user' 
                                ? 'bg-indigo-600 text-white rounded-tr-sm' 
                                : 'bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-tl-sm'">
                        <span x-text="msg.content"></span>
                    </div>
                </div>
            </template>
            <div x-show="isTyping" class="text-xs text-slate-500 italic px-1 animate-pulse">duck ngetik...</div>
        </div>

        <!-- Chat Input -->
        <form @submit.prevent="sendMessage()" class="border-t border-slate-100 dark:border-slate-800 p-2 flex gap-2 bg-white dark:bg-slate-900">
            <input type="text" x-model="chatInput" placeholder="ngomong..." class="flex-1 bg-slate-100 dark:bg-slate-800 text-sm rounded-xl px-3 py-1.5 border-none focus:ring-1 focus:ring-indigo-500 outline-none text-slate-800 dark:text-white" :disabled="isTyping" autocomplete="off">
            <button type="submit" class="w-8 h-8 rounded-full bg-indigo-500 hover:bg-indigo-600 flex items-center justify-center text-white transition-colors disabled:opacity-50" :disabled="!chatInput.trim() || isTyping">
                <i class="ph-bold ph-paper-plane-right text-sm"></i>
            </button>
        </form>
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
            
            init() {
                // Initial welcome after 5s
                setTimeout(() => this.triggerEvent('dashboard'), 5000);
                
                // Track Idle (3 minutes)
                this.resetIdle();
                window.addEventListener('mousemove', () => this.resetIdle());
                window.addEventListener('keydown', () => this.resetIdle());

                // Listen to Pomodoro finishes globally via window events or Alpine store
                // We'll dispatch a custom event from the pomodoro widget later
                window.addEventListener('pomodoro-finished', () => {
                    this.triggerEvent('pomodoro_finish');
                });

                // Start random event scheduler
                this.scheduleRandomEvent();
            },

            scheduleRandomEvent() {
                clearTimeout(this.randomTimer);
                // Random time between 3 to 10 minutes (180,000 to 600,000 ms)
                const nextTime = Math.floor(Math.random() * (600000 - 180000 + 1)) + 180000;
                
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
                this.currentMessage = message;
                this.bubbleVisible = true;
                this.chatVisible = false;
                
                // Random mood everytime Duck speaks (except if it is specifically set to ngantuk or dengerin)
                if (this.mood !== 'ngantuk' && this.mood !== 'dengerin') {
                    const moodList = ['santai', 'bengong', 'nyinyir', 'males', 'pede', 'asbun', 'laper', 'konser', 'overthinking'];
                    this.mood = moodList[Math.floor(Math.random() * moodList.length)];
                }
                
                // Add to chat history automatically just in case user opens it
                if (this.chatHistory.length === 0 || this.chatHistory[this.chatHistory.length - 1].content !== message) {
                    this.chatHistory.push({ role: 'duck', content: message });
                }

                clearTimeout(this.bubbleTimer);
                // Hide bubble after 5s or reading time
                const hideTime = Math.max(4000, message.length * 100); 
                this.bubbleTimer = setTimeout(() => {
                    this.bubbleVisible = false;
                    // Reset to santai after talking, unless idle will kick in
                    if (this.mood !== 'ngantuk') this.mood = 'santai';
                }, hideTime);
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
                setTimeout(() => {
                    this.scrollToBottom();
                    // focus input
                    const input = this.$el.querySelector('input');
                    if(input) input.focus();
                }, 100);
            },

            closeChat() {
                this.chatVisible = false;
            },

            async sendMessage() {
                if (!this.chatInput.trim()) return;
                
                const userMsg = this.chatInput.trim();
                this.chatHistory.push({ role: 'user', content: userMsg });
                this.chatInput = '';
                this.isTyping = true;
                this.scrollToBottom();

                try {
                    const res = await fetch('/duck/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ message: userMsg })
                    });
                    const data = await res.json();
                    
                    if (data.success) {
                        this.chatHistory.push({ role: 'duck', content: data.content });
                    } else {
                        this.chatHistory.push({ role: 'duck', content: 'males jawab.' });
                    }
                } catch (e) {
                    this.chatHistory.push({ role: 'duck', content: 'ngantuk gue.' });
                } finally {
                    this.isTyping = false;
                    this.scrollToBottom();
                }
            },

            scrollToBottom() {
                setTimeout(() => {
                    const container = document.getElementById('duck-chat-history');
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                }, 50);
            }
        }));
    });
</script>