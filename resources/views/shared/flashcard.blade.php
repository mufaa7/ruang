<x-app-layout>
    <x-slot name="pageTitle">Flashcard: {{ $flashcardSet->title }}</x-slot>

    <div class="max-w-4xl mx-auto pb-12" x-data="flashcardApp()">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <a href="{{ $backUrl }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-stone-600 transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cabut Dulu
                </a>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-geist flex items-center gap-2">
                    🃏 {{ $flashcardSet->title }}
                </h1>
            </div>
            <div class="bg-stone-100 dark:bg-neutral-900/10 px-4 py-2 rounded-xl text-neutral-900 dark:text-stone-500 font-bold font-geist">
                <template x-if="currentIndex < totalCards">
                    <span>Kartu <span x-text="currentIndex + 1"></span> dari <span x-text="totalCards"></span></span>
                </template>
                <template x-if="currentIndex === totalCards">
                    <span>Tamat! <i class="ph ph-flag text-[1.1em] align-middle"></i></span>
                </template>
            </div>
        </div>

        <div class="relative w-full max-w-2xl mx-auto perspective-1000" style="min-height: 400px;">
            <!-- Progress Bar -->
            <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-1.5 mb-8 overflow-hidden">
                <div class="bg-neutral-900 h-1.5 rounded-full transition-all duration-300" :style="'width: ' + (Math.min(currentIndex + 1, totalCards) / totalCards * 100) + '%'"></div>
            </div>

            <!-- Flashcard -->
            <template x-if="totalCards > 0 && currentIndex < totalCards">
                <div class="relative w-full h-80 cursor-pointer group" @click="flipCard">
                    <div class="absolute inset-0 w-full h-full transition-all duration-500 transform-style-3d shadow-xl rounded-[24px]" :class="{'rotate-y-180': isFlipped}">
                        
                        <!-- Front (Istilah) -->
                        <div class="absolute inset-0 w-full h-full backface-hidden bg-white dark:bg-slate-800 rounded-[24px] border-2 border-slate-200 dark:border-slate-700 flex flex-col items-center justify-center p-8 text-center"
                            :class="{'invisible': isFlipped && !isAnimating}">
                            <span class="absolute top-6 left-6 text-xs font-bold text-slate-400 uppercase tracking-widest">Istilah</span>
                            <h2 class="text-3xl font-bold text-slate-800 dark:text-white font-geist" x-text="currentCard.front"></h2>
                            <div class="absolute bottom-6 flex items-center text-slate-400 text-sm font-medium gap-2">
                                <svg class="w-5 h-5 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/></svg>
                                Klik untuk balik
                            </div>
                        </div>

                        <!-- Back (Definisi) -->
                        <div class="absolute inset-0 w-full h-full backface-hidden bg-neutral-900 dark:bg-neutral-900 rounded-[24px] rotate-y-180 flex flex-col items-center justify-center p-8 text-center text-white overflow-y-auto"
                            :class="{'invisible': !isFlipped && !isAnimating}">
                            <span class="absolute top-6 left-6 text-xs font-bold text-stone-300 uppercase tracking-widest">Definisi</span>
                            <p class="text-xl font-medium leading-relaxed mt-4" x-text="currentCard.back"></p>
                        </div>

                    </div>
                </div>
            </template>

            <!-- End of Cards State -->
            <template x-if="totalCards > 0 && currentIndex === totalCards">
                <div class="w-full h-80 bg-white dark:bg-slate-900 rounded-[24px] border-2 border-stone-200 dark:border-neutral-800/20 flex flex-col items-center justify-center p-8 text-center shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-stone-100 dark:bg-neutral-900/10 flex items-center justify-center text-neutral-900 dark:text-stone-500 mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white font-geist mb-2">Tamat Riwayat Kartunya! <i class="ph ph-flag text-[1.1em] align-middle"></i></h2>
                    <p class="text-slate-500 dark:text-slate-400 mb-8 max-w-sm">Jujur lu beneran hafal apa cuma spam pencet Next?</p>
                    
                    <div class="flex items-center gap-4">
                        <button @click="currentIndex = 0; isFlipped = false" class="px-6 py-3 rounded-xl font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                            Sok-sokan Ngulang
                        </button>
                        
                        @if($flashcardSet->quiz_id)
                        <a href="{{ route('latihan.quiz.show', $flashcardSet->quiz_id) }}" class="px-6 py-3 rounded-xl font-bold text-white bg-neutral-900 hover:bg-neutral-900 transition-colors shadow-lg shadow-neutral-900/20 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            Kuis
                        </a>
                        @endif
                    </div>
                </div>
            </template>
            
            <template x-if="totalCards === 0">
                <div class="w-full h-80 bg-slate-50 dark:bg-slate-900 rounded-[24px] border-2 border-dashed border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center p-8 text-center">
                    <div class="text-4xl mb-4"><i class="ph ph-question text-[1.1em] align-middle"></i>‍<i class="ph ph-gender-male text-[1.1em] align-middle"></i>️</div>
                    <h2 class="text-lg font-bold font-geist text-slate-900 dark:text-white mb-2">Kuis</h2>
                    <p class="text-slate-500">Belum ada kartu yang tersedia di set ini.</p>
                </div>
            </template>

            <!-- Navigation Controls -->
            <template x-if="totalCards > 0 && currentIndex < totalCards">
                <div class="flex justify-center items-center gap-6 mt-10">
                    <button @click="prevCard" :disabled="currentIndex === 0" class="p-4 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-stone-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    
                    <button @click="nextCard" :disabled="currentIndex === totalCards" class="p-4 rounded-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-stone-600 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </template>
            
            <template x-if="totalCards > 0 && currentIndex < totalCards">
                <div class="text-center mt-6">
                     <p class="text-sm text-slate-500 dark:text-slate-400">Gunakan panah <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-md mx-1 font-mono text-xs border border-slate-200 dark:border-slate-700">←</kbd> dan <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-md mx-1 font-mono text-xs border border-slate-200 dark:border-slate-700">→</kbd> di keyboard untuk navigasi. <kbd class="px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-md mx-1 font-mono text-xs border border-slate-200 dark:border-slate-700">Spasi</kbd> untuk membalik kartu.</p>
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

    @push('scripts')
    <script>
        window.flashcardApp = function() {
            return {
                cards: @json($flashcardSet->flashcards),
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
                    // Keyboard navigation
                    window.addEventListener('keydown', (e) => {
                        // Jangan trigger kalau lagi ngetik di input
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
            };
        };
    </script>
    @endpush
</x-app-layout>
