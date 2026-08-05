<div id="duck-mascot"
     x-data="duckSystem()"
     @click.outside="closeChat()"   
     class="fixed -top-6 -right-4 md:right-5 z-[100] pointer-events-auto select-none font-sans flex flex-col items-end"
     x-cloak>

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
    <div class="absolute top-5 -left-14 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none z-20 -rotate-3 group-hover:animate-pulse">
        <div class="relative bg-amber-400 text-stone-900 font-black px-3 py-1 text-xs rounded-lg border-2 border-stone-900 shadow-[3px_3px_0px_0px_rgba(28,25,23,1)]">
            WOTCHER!
            <!-- Tail balon kata (pointing right to the duck) -->
            <div class="absolute -right-8 top-5 w-0 h-0 border-t-[5px] border-t-transparent border-b-[5px] border-b-transparent border-l-[7px] border-l-stone-900"></div>
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
         class="absolute top-[50px] right-[120px] mt-2 bg-white dark:bg-slate-800 text-slate-800 dark:text-white px-5 py-3 rounded-2xl rounded-tr-none shadow-lg border border-slate-200 dark:border-slate-700 min-w-[140px] max-w-[260px] text-base font-medium shadow-[0_4px_20px_rgba(0,0,0,0.08)] cursor-pointer z-20"
         @click="openChat()">
        <p x-text="currentMessage" class="leading-relaxed"></p>
    </div>

    <!-- Mini Chat Popover (Now below Duck) -->
    <div x-show="chatVisible"
         @click.stop
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
         id="duck-chat-popover"
         class="fixed top-36 right-1 w-72 md:absolute md:top-32 md:right-2 bg-white dark:bg-slate-900 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden flex flex-col shadow-[0_8px_30px_rgba(0,0,0,0.12)] z-[200]">
        
        <!-- Chat History -->
        <div class="p-3 max-h-48 overflow-y-auto space-y-3 bg-white dark:bg-slate-900" id="duck-chat-history">
            <template x-for="msg in chatHistory">
                <div class="flex flex-col" :class="msg.role === 'user' ? 'items-end' : 'items-start'">
                    <div class="px-3.5 py-1.5 text-[14.5px] inline-block shadow-none relative"
                         style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; text-align: left; text-justify: none; word-spacing: 0; overflow-wrap: break-word;"
                         :class="msg.role === 'user' 
                                ? 'bg-[#007AFF] text-white rounded-[18px] rounded-br-[4px]' 
                                : 'bg-[#E5E5EA] dark:bg-[#262628] text-black dark:text-white rounded-[18px] rounded-bl-[4px]'">
                        <span x-text="msg.content"></span>
                    </div>
                    <!-- Read Receipt -->
                    <template x-if="msg.role === 'user' && msg.status && isLastUserMessage(msg)">
                        <div class="flex justify-end w-full mt-1 pr-1">
                            <span class="text-[10px] text-slate-400 font-sans tracking-wide" x-text="msg.status === 'seen' ? 'Seen' : 'Delivered'"></span>
                        </div>
                    </template>
                </div>
            </template>
            <div x-show="isTyping" class="text-[11px] text-slate-400 italic px-2 animate-pulse font-sans">duck ngetik...</div>
        </div>

        <!-- Chat Input -->
        <div class="border-t border-slate-200 dark:border-[#2C2C2E] p-2 flex gap-2 bg-[#F9F9F9] dark:bg-[#1C1C1E] font-sans items-center">
            <input type="text" x-model="chatInput" @input="delaySend()" @keydown.enter.prevent.stop="sendMessage()" @focus="const _y = window.scrollY; setTimeout(() => window.scrollTo(0, _y), 50)" placeholder="iMessage" class="flex-1 bg-white dark:bg-[#000000] text-[15px] rounded-full px-4 py-1.5 border border-[#C8C8CC] dark:border-[#3A3A3C] focus:ring-0 focus:border-[#C8C8CC] dark:focus:border-[#3A3A3C] outline-none text-black dark:text-white transition-none shadow-none min-w-0" autocomplete="off" inputmode="text" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; font-size: 16px; word-spacing: 0;">
            <button @click.prevent.stop="sendMessage()" class="w-8 h-8 rounded-full bg-[#007AFF] hover:opacity-80 flex items-center justify-center text-white transition-opacity disabled:opacity-30 shrink-0" :disabled="!chatInput.trim()">
                <i class="ph-bold ph-arrow-up text-[16px]"></i>

            </button>
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
                // Random time between 30s to 90s (30,000 to 90,000 ms) biar bebek sering ngoceh
                const nextTime = Math.floor(Math.random() * (90000 - 30000 + 1)) + 30000;
                
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
                let messages = message.split('||').map(m => m.trim()).filter(m => m);
                this.currentMessage = messages[0];
                this.bubbleVisible = true;
                this.chatVisible = false;
                
                // Random mood everytime Duck speaks (except if it is specifically set to ngantuk or dengerin)
                if (this.mood !== 'ngantuk' && this.mood !== 'dengerin') {
                    const moodList = ['santai', 'bengong', 'nyinyir', 'males', 'pede', 'asbun', 'laper', 'konser', 'overthinking'];
                    this.mood = moodList[Math.floor(Math.random() * moodList.length)];
                }
                
                // Add to chat history automatically just in case user opens it
                messages.forEach(msg => {
                    if (this.chatHistory.length === 0 || this.chatHistory[this.chatHistory.length - 1].content !== msg) {
                        this.chatHistory.push({ role: 'duck', content: msg });
                    }
                });

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
                            if (this.mood !== 'ngantuk') this.mood = 'santai';
                        }
                    }, Math.max(4000, this.currentMessage.length * 100));
                };
                
                processNextMessage(1);
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
                // Reposition chat below duck on mobile
                if (window.innerWidth < 768) {
                    this.$nextTick(() => {
                        const duck = document.getElementById('duck-mascot');
                        const chat = document.getElementById('duck-chat-popover');
                        if (duck && chat) {
                            const rect = duck.getBoundingClientRect();
                            chat.style.top = Math.min(rect.bottom - 36, window.innerHeight - 280) + 'px';
                            chat.style.right = '4px';
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
                this.chatInput = '';
                this.isTyping = false; // Jangan tampilkan typing dulu
                this.scrollToBottom();

                // Tunggu 0.8 detik → Seen, lalu baru tampilkan isTyping
                await new Promise(resolve => setTimeout(resolve, 800));
                const mSeen = this.chatHistory.find(x => x.id === msgId);
                if (mSeen) mSeen.status = 'seen';
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
                        let messages = data.content.split('||').map(m => m.trim()).filter(m => m);
                        this.chatHistory.push({ role: 'duck', content: messages[0] });
                        
                        if (messages.length > 1) {
                            let delay = 800;
                            for (let i = 1; i < messages.length; i++) {
                                setTimeout(() => {
                                    this.isTyping = true;
                                    this.scrollToBottom();
                                    setTimeout(() => {
                                        this.chatHistory.push({ role: 'duck', content: messages[i] });
                                        this.isTyping = false;
                                        this.scrollToBottom();
                                    }, 1000);
                                }, delay);
                                delay += 1800;
                            }
                        }
                    } else {
                        this.chatHistory.push({ role: 'duck', content: 'males jawab.' });
                    }
                } catch (e) {
                    this.chatHistory.push({ role: 'duck', content: 'ngantuk gue.' });
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