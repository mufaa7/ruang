<x-app-layout>
    <x-slot name="pageTitle">Flashcard: {{ $flashcardSet->title }}</x-slot>

    <div class="max-w-4xl mx-auto pb-12 animate-fadeIn" x-data="{
        cards: @js($flashcardSet->flashcards),
        currentIndex: 0,
        isFlipped: false,
        isAnimating: false,
        
        get totalCards() {
            return this.cards.length;
        },
        
        get currentCard() {
            return this.cards[this.currentIndex] || {front: '', back: ''};
        },
        
        flipCard() {
            if (this.isAnimating || this.totalCards === 0) return;
            this.isAnimating = true;
            this.isFlipped = !this.isFlipped;
            setTimeout(() => {
                this.isAnimating = false;
            }, 500); // Matches CSS duration
        },
        
        nextCard() {
            if (this.currentIndex < this.totalCards) {
                this.isFlipped = false;
                setTimeout(() => {
                    this.currentIndex++;
                }, 150);
            }
        },
        
        prevCard() {
            if (this.currentIndex > 0) {
                this.isFlipped = false;
                setTimeout(() => {
                    this.currentIndex--;
                }, 150);
            }
        },
        
        init() {
            window.addEventListener('keydown', (e) => {
                if (['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) return;

                if (e.key === 'ArrowRight') {
                    this.nextCard();
                } else if (e.key === 'ArrowLeft') {
                    this.prevCard();
                } else if (e.key === ' ' || e.key === 'Spacebar') {
                    e.preventDefault();
                    this.flipCard();
                }
            });
        }
    }">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <a href="{{ $backUrl }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cabut Dulu
                </a>
                <h1 class="text-2xl font-bold text-white font-geist flex items-center gap-2">
                    🃏 {{ $flashcardSet->title }}
                </h1>
            </div>
            <div class="bg-white/5 border border-white/10 px-4 py-2 rounded-xl text-slate-300 font-bold font-geist backdrop-blur-xl shadow-sm">
                <template x-if="currentIndex < totalCards">
                    <span>Kartu <span x-text="currentIndex + 1" class="text-white"></span> dari <span x-text="totalCards" class="text-white"></span></span>
                </template>
                <template x-if="currentIndex === totalCards">
                    <span class="text-amber-400">Tamat! <i class="ph-fill ph-flag text-[1.1em] align-middle"></i></span>
                </template>
            </div>
        </div>

        <div class="relative w-full max-w-2xl mx-auto perspective-1000" style="min-height: 400px;">
            <!-- Progress Bar -->
            <div class="w-full bg-white/5 border border-white/10 rounded-full h-2 mb-10 overflow-hidden backdrop-blur-xl">
                <div class="bg-white h-2 rounded-full transition-all duration-300 shadow-[0_0_10px_rgba(255,255,255,0.5)]" :style="'width: ' + (Math.min(currentIndex + 1, totalCards) / totalCards * 100) + '%'"></div>
            </div>

            <!-- Flashcard -->
            <template x-if="totalCards > 0 && currentIndex < totalCards">
                <div class="relative w-full h-80 cursor-pointer group" @click="flipCard">
                    <div class="absolute inset-0 w-full h-full transition-all duration-500 transform-style-3d shadow-2xl rounded-[32px]" :class="{'rotate-y-180': isFlipped}">
                        
                        <!-- Front (Istilah) -->
                        <div class="absolute inset-0 w-full h-full backface-hidden bg-black/40 backdrop-blur-2xl rounded-[32px] border-2 border-white/10 flex flex-col items-center justify-center p-8 text-center"
                            :class="{'invisible': isFlipped && !isAnimating}">
                            <span class="absolute top-8 left-8 text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-white/5 px-3 py-1 rounded-full border border-white/10">Istilah</span>
                            <h2 class="text-4xl md:text-5xl font-bold text-white font-geist leading-tight px-4 drop-shadow-md" x-text="currentCard.front"></h2>
                            <div class="absolute bottom-8 flex items-center text-slate-400 text-sm font-medium gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <svg class="w-5 h-5 animate-bounce text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                Klik untuk balik
                            </div>
                        </div>

                        <!-- Back (Definisi) -->
                        <div class="absolute inset-0 w-full h-full backface-hidden bg-white rounded-[32px] rotate-y-180 flex flex-col items-center justify-center p-8 text-center text-neutral-900 overflow-y-auto shadow-inner"
                            :class="{'invisible': !isFlipped && !isAnimating}">
                            <span class="absolute top-8 left-8 text-[11px] font-bold text-neutral-500 uppercase tracking-widest bg-neutral-100 px-3 py-1 rounded-full border border-neutral-200">Definisi</span>
                            <p class="text-2xl md:text-3xl font-bold leading-snug mt-8 px-4" x-text="currentCard.back"></p>
                        </div>

                    </div>
                </div>
            </template>

            <!-- End of Cards State -->
            <template x-if="totalCards > 0 && currentIndex === totalCards">
                <div class="w-full h-80 bg-black/40 backdrop-blur-2xl rounded-[32px] border border-white/10 flex flex-col items-center justify-center p-8 text-center shadow-2xl">
                    <div class="w-20 h-20 rounded-3xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 mb-6 shadow-[0_0_30px_rgba(245,158,11,0.2)]">
                        <i class="ph-fill ph-check-circle text-4xl"></i>
                    </div>
                    <h2 class="text-3xl font-bold text-white font-geist mb-3">Tamat Riwayat Kartunya!</h2>
                    <p class="text-slate-400 mb-8 max-w-sm text-lg">Jujur lu beneran hafal apa cuma spam pencet Next?</p>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4">
                        <button @click="currentIndex = 0; isFlipped = false" class="px-6 py-3.5 rounded-2xl font-bold text-white bg-white/10 border border-white/20 hover:bg-white/20 transition-colors">
                            Sok-sokan Ngulang
                        </button>
                        
                        @if($flashcardSet->quiz_id)
                        <a href="{{ route('latihan.quiz.show', $flashcardSet->quiz_id) }}" class="px-6 py-3.5 rounded-2xl font-bold text-black bg-white hover:bg-neutral-200 transition-colors shadow-[0_0_20px_rgba(255,255,255,0.3)] flex items-center gap-2">
                            <i class="ph-bold ph-game-controller text-lg"></i>
                            Mulai Kuis
                        </a>
                        @endif
                    </div>
                </div>
            </template>
            
            <template x-if="totalCards === 0">
                <div class="w-full h-80 bg-black/20 backdrop-blur-xl rounded-[32px] border border-dashed border-white/20 flex flex-col items-center justify-center p-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-white/5 border border-white/10 flex items-center justify-center mb-4">
                        <i class="ph-bold ph-ghost text-2xl text-slate-500"></i>
                    </div>
                    <h2 class="text-xl font-bold font-geist text-white mb-2">Kosong Melompong</h2>
                    <p class="text-slate-400">Belum ada kartu yang tersedia di set ini.</p>
                </div>
            </template>

            <!-- Navigation Controls -->
            <template x-if="totalCards > 0 && currentIndex < totalCards">
                <div class="flex justify-center items-center gap-6 mt-12">
                    <button @click="prevCard" :disabled="currentIndex === 0" class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all shadow-lg active:scale-95">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    
                    <button @click="nextCard" :disabled="currentIndex === totalCards" class="p-5 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all shadow-lg active:scale-95">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </template>
            
            <template x-if="totalCards > 0 && currentIndex < totalCards">
                <div class="text-center mt-8">
                     <p class="text-sm text-slate-500">Gunakan panah <kbd class="px-2 py-1 bg-white/10 rounded-md mx-1 font-mono text-xs border border-white/10 text-slate-300">←</kbd> dan <kbd class="px-2 py-1 bg-white/10 rounded-md mx-1 font-mono text-xs border border-white/10 text-slate-300">→</kbd> di keyboard untuk navigasi. <kbd class="px-2 py-1 bg-white/10 rounded-md mx-1 font-mono text-xs border border-white/10 text-slate-300">Spasi</kbd> untuk membalik kartu.</p>
                </div>
            </template>
        </div>
    </div>

    @push('styles')
    <style>
        .perspective-1000 {
            perspective: 1000px;
        }
        .transform-style-3d {
            transform-style: preserve-3d;
        }
        .backface-hidden {
            backface-visibility: hidden;
        }
        .rotate-y-180 {
            transform: rotateY(180deg);
        }
    </style>
    @endpush


</x-app-layout>
