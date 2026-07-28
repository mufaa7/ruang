<div id="duck-mascot"
     x-data="duckSystem()"
     class="fixed top-4 right-8 z-[100] pointer-events-auto select-none font-sans flex flex-col items-end"
     x-cloak
     @click.away="closeChat()">

    <div class="relative w-24 h-24 cursor-pointer group z-10" 
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
    <div class="absolute -top-6 -right-10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-20 rotate-6 group-hover:animate-pulse">
        <div class="bg-amber-400 text-stone-900 font-black px-3 py-1 text-xs rounded-lg border-2 border-stone-900 shadow-[3px_3px_0px_0px_rgba(28,25,23,1)]">
            WOTCHER!
            <!-- Tail balon kata -->
            <div class="absolute -bottom-2 left-4 w-0 h-0 border-l-[6px] border-l-transparent border-t-[8px] border-t-stone-900 border-r-[6px] border-r-transparent"></div>
        </div>
    </div>

    <!-- === SVG DUCK LIAM GALLAGHER === -->
    <svg width="100%" height="100%" viewBox="0 0 120 130" fill="none" xmlns="http://www.w3.org/2000/svg" class="drop-shadow-xl transition-transform duration-300 group-hover:-translate-y-2 group-active:scale-95">
        
        <g class="duck-body">
            <!-- === BODY (Green Parka) === -->
            <ellipse cx="52" cy="90" rx="28" ry="30" class="fill-[#4a5547] dark:fill-[#3f473d]"/>
            <line x1="52" y1="65" x2="52" y2="115" class="stroke-[#2d332b]" stroke-width="2" stroke-dasharray="4 4"/>
            <!-- Parka Pocket -->
            <rect x="25" y="90" width="14" height="10" rx="2" class="stroke-[#2d332b]" stroke-width="1.5" fill="none"/>
            <!-- Fur Collar -->
            <ellipse cx="52" cy="67" rx="24" ry="10" class="fill-yellow-700/90 dark:fill-yellow-800/90"/>
            <ellipse cx="52" cy="64" rx="20" ry="8" class="fill-yellow-600/90 dark:fill-yellow-700/90"/>

            <!-- === HEAD === -->
            <circle cx="58" cy="45" r="26" class="fill-slate-800 dark:fill-slate-200"/>

            <!-- === BEAK === -->
            <path d="M78 42 Q 102 38 99 48 Q 96 56 78 55 Z" class="fill-amber-500"/>
            <line x1="78" y1="48" x2="98" y2="46" class="stroke-amber-700" stroke-width="1" opacity="0.6"/>

            <!-- === ROUND SUNGLASSES (Liam Style) === -->
            <g>
                <circle cx="50" cy="42" r="9" class="fill-gray-900 stroke-gray-400 dark:stroke-gray-500" stroke-width="2"/>
                <circle cx="70" cy="42" r="9" class="fill-gray-900 stroke-gray-400 dark:stroke-gray-500" stroke-width="2"/>
                <path d="M59 42 Q 60 40 61 42" class="stroke-gray-400 dark:stroke-gray-500" stroke-width="2" fill="none"/> <!-- Bridge -->
                <path d="M41 40 Q 35 38 32 40" class="stroke-gray-400 dark:stroke-gray-500" stroke-width="2" stroke-linecap="round" fill="none"/> <!-- Arm L -->
                <path d="M79 40 Q 85 38 88 40" class="stroke-gray-400 dark:stroke-gray-500" stroke-width="2" stroke-linecap="round" fill="none"/> <!-- Arm R -->
                <circle cx="47" cy="39" r="2" fill="white" opacity="0.5"/>
                <circle cx="67" cy="39" r="2" fill="white" opacity="0.5"/>
            </g>
        </g>

        <!-- === TAMBOURINE === -->
        <g class="tambourine-group" transform="rotate(-18 23 80)">
            <circle cx="23" cy="80" r="16" fill="none" class="stroke-amber-800 dark:stroke-amber-700" stroke-width="6" stroke-linecap="round"/>
            <circle cx="23" cy="80" r="11" fill="transparent"/>
            
            <g class="fill-gray-300 stroke-gray-500" stroke-width="0.5">
                <circle cx="23" cy="61" r="2.5"/><circle cx="23" cy="99" r="2.5"/>
                <circle cx="42" cy="80" r="2.5"/><circle cx="4" cy="80" r="2.5"/>
                <circle cx="36" cy="67" r="2.5"/><circle cx="10" cy="67" r="2.5"/>
                <circle cx="36" cy="93" r="2.5"/><circle cx="10" cy="93" r="2.5"/>
            </g>

            <g class="stroke-gray-700" stroke-width="1">
                <line x1="23" y1="63" x2="23" y2="67"/><line x1="23" y1="93" x2="23" y2="97"/>
                <line x1="40" y1="80" x2="36" y2="80"/><line x1="6" y1="80" x2="10" y2="80"/>
                <line x1="34" y1="69" x2="31" y2="72"/><line x1="12" y1="69" x2="15" y2="72"/>
                <line x1="34" y1="91" x2="31" y2="88"/><line x1="12" y1="91" x2="15" y2="88"/>
            </g>
        </g>

        <!-- === WING (Matching Parka Color) === -->
        <path d="M36 77 Q41 75 46 79" class="stroke-[#2d332b]" stroke-width="4" stroke-linecap="round" fill="none"/>
        
        <!-- === LEGS === -->
        <path d="M42 118 L 38 130" stroke="#F59E0B" stroke-width="4" stroke-linecap="round"/>
        <path d="M60 118 L 62 130" stroke="#F59E0B" stroke-width="4" stroke-linecap="round"/>
        <!-- Feet -->
        <path d="M38 130 Q 33 133 28 131 Q 33 128 38 130" stroke="#F59E0B" stroke-width="2" fill="#F59E0B"/>
        <path d="M62 130 Q 67 133 72 131 Q 67 128 62 130" stroke="#F59E0B" stroke-width="2" fill="#F59E0B"/>

        <!-- === MOODS === -->
        
        <!-- NGANTUK (zzz) -->
        <g x-show="mood === 'ngantuk'" class="animate-pulse">
            <text x="90" y="32" fill="#94a3b8" font-size="14" font-weight="bold" font-family="sans-serif">z</text>
            <text x="100" y="20" fill="#94a3b8" font-size="10" font-weight="bold" font-family="sans-serif">z</text>
        </g>
        
        <!-- DENGERIN (music notes) -->
        <g x-show="mood === 'dengerin'" class="animate-bounce">
            <text x="90" y="32" fill="#818cf8" font-size="14" font-family="sans-serif">♪</text>
            <text x="103" y="22" fill="#818cf8" font-size="10" font-family="sans-serif">♫</text>
        </g>

        <!-- BENGONG (...) -->
        <g x-show="mood === 'bengong'" class="animate-pulse">
            <text x="90" y="30" fill="#cbd5e1" font-size="16" font-weight="bold" font-family="sans-serif">...</text>
        </g>

        <!-- NYINYIR (!#@) -->
        <g x-show="mood === 'nyinyir'" class="animate-bounce">
            <text x="90" y="30" fill="#ef4444" font-size="14" font-weight="bold" font-family="sans-serif">#@!</text>
        </g>

        <!-- MALES (Sigh drop) -->
        <g x-show="mood === 'males'">
            <path d="M 85 25 Q 90 20 95 30" stroke="#94a3b8" stroke-width="2" fill="none" class="opacity-60"/>
            <circle cx="95" cy="32" r="1.5" fill="#38bdf8" class="animate-pulse"/>
        </g>

        <!-- PEDE (Sparkles) -->
        <g x-show="mood === 'pede'" class="animate-pulse">
            <text x="90" y="30" fill="#fbbf24" font-size="14" font-family="sans-serif">✨</text>
        </g>

        <!-- ASBUN (Talkative bubble) -->
        <g x-show="mood === 'asbun'" class="animate-bounce">
            <text x="90" y="30" fill="#38bdf8" font-size="14" font-family="sans-serif">🗯️</text>
        </g>

        <!-- LAPER (Chicken leg / food) -->
        <g x-show="mood === 'laper'" class="animate-pulse">
            <text x="90" y="30" fill="#f87171" font-size="14" font-family="sans-serif">🍗</text>
        </g>

        <!-- KONSER (Mic) -->
        <g x-show="mood === 'konser'" class="animate-bounce">
            <text x="90" y="30" fill="#a855f7" font-size="14" font-family="sans-serif">🎤</text>
            <text x="105" y="20" fill="#a855f7" font-size="10" font-family="sans-serif">♪</text>
        </g>

        <!-- OVERTHINKING (Swirl) -->
        <g x-show="mood === 'overthinking'" class="animate-spin" style="transform-origin: 97px 24px;">
            <text x="90" y="30" fill="#64748b" font-size="14" font-family="sans-serif">🌀</text>
        </g>
    </svg>
    </div>

    <!-- Speech Bubble (Now below Duck) -->
    <div x-show="bubbleVisible" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-2 scale-95"
         class="absolute top-20 right-0 mt-2 bg-white dark:bg-slate-800 text-slate-800 dark:text-white px-5 py-3 rounded-2xl rounded-tr-none shadow-lg border border-slate-200 dark:border-slate-700 min-w-[140px] max-w-[260px] text-base font-medium shadow-[0_4px_20px_rgba(0,0,0,0.08)] cursor-pointer z-20"
         @click="openChat()">
        <p x-text="currentMessage" class="leading-relaxed"></p>
    </div>

    <!-- Mini Chat Popover (Now below Duck) -->
    <div x-show="chatVisible"
         @click.away="closeChat()"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
         class="absolute top-20 right-0 mt-2 w-72 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col shadow-[0_8px_30px_rgba(0,0,0,0.12)] z-20">
        
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