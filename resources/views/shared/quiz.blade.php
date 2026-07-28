<x-app-layout>
    <x-slot name="pageTitle">{{ $quiz->title }}</x-slot>

    @php
        // $result sudah di-pass dari controller (null jika belum pernah dikerjakan)
        $score = $result['score'] ?? null;
        $total   = $quiz->questions->count();
        $mcqCount = $quiz->questions->where('type','multiple_choice')->count();

        // Pesan random berdasarkan skor
        $msgs = [
            100 => ['🏆 Sempurna!', 'Nilai lu memuaskan, pertahankan (kalo bisa).'],
            80  => ['🔥 Luar Biasa!', 'Dikit lagi nyempurnain. Tetep keren sih.'],
            60  => ['😤 Lumayan.', 'Masih bisa lebih baik. Coba lagi abis ngopi.'],
            40  => ['💀 Aduh.', 'Belajar lagi ya. Pembahasan di bawah tuh gratis lho.'],
            0   => ['🤦 Serius?', 'Ini kuis apa random klik? Buka catatan dulu kali.'],
        ];
        $msgKey = 0;
        foreach ([100,80,60,40,0] as $threshold) {
            if ($score >= $threshold) { $msgKey = $threshold; break; }
        }
        [$msgTitle, $msgBody] = $msgs[$msgKey];
    @endphp

    <div x-data="{ showPembahasan: {{ $result ? 'false' : 'false' }} }" class="max-w-2xl mx-auto pb-20">

        {{-- ── Banner Header ── --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <a href="{{ $backUrl }}" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-indigo-600 transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cabut Dulu
                </a>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-geist flex items-center gap-2">
                    📝 {{ $quiz->title }}
                </h1>
                <p class="text-sm text-slate-500 font-medium mt-1">
                    {{ $quiz->subject->name ?? 'Kuis' }} · {{ $total }} soal
                    @if($quiz->time_limit_minutes) · {{ $quiz->time_limit_minutes }} menit @endif
                    @if($result) · <span class="text-indigo-500">Mode Review</span> @endif
                </p>
            </div>
            @if($result)
            {{-- Skor badge di kanan --}}
            <div class="shrink-0 text-center bg-white dark:bg-slate-900 p-3 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center font-bold text-2xl
                    {{ $score >= 70 ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/40 text-rose-500' }}">
                    {{ $score }}
                </div>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">Nilai</p>
            </div>
            @endif
        </div>

        {{-- ── Hasil Panel ── --}}
        @if($result)
        <div class="mb-8 rounded-2xl border {{ $score >= 70 ? 'border-emerald-200 dark:border-emerald-800 bg-emerald-50/50 dark:bg-emerald-900/10' : 'border-rose-200 dark:border-rose-800 bg-rose-50/50 dark:bg-rose-900/10' }} p-5">
            <div class="flex items-center gap-4">
                <div class="text-3xl">{{ $score >= 70 ? '🏆' : '😤' }}</div>
                <div>
                    <p class="font-bold text-slate-900 dark:text-white text-base">{{ $msgTitle }}</p>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $msgBody }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t {{ $score >= 70 ? 'border-emerald-100 dark:border-emerald-800/50' : 'border-rose-100 dark:border-rose-800/50' }} flex items-center justify-between">
                <p class="text-sm font-semibold text-slate-600 dark:text-slate-300">
                    Benar <span class="font-bold text-slate-900 dark:text-white">{{ $result['correct'] }}/{{ $result['total'] }}</span>
                    soal PG
                    @if($quiz->questions->where('type','essay')->count() > 0)
                        <span class="text-slate-400 font-normal">&nbsp;· essay belum dihitung</span>
                    @endif
                </p>
                <div class="flex gap-2">
                    <button @click="showPembahasan = !showPembahasan"
                            class="text-xs font-bold px-3 py-1.5 rounded-lg border
                                {{ $score >= 70
                                    ? 'border-emerald-300 dark:border-emerald-700 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/30'
                                    : 'border-rose-300 dark:border-rose-700 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/30' }}
                                transition-colors">
                        <span x-text="showPembahasan ? 'Sembunyikan' : 'Lihat Pembahasan'"></span>
                    </button>
                    <a href="{{ $backUrl }}"
                       class="text-xs font-bold px-3 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        ← Kembali
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Form / Review Soal ── --}}
        <form action="{{ route('latihan.quiz.submit', $quiz->id) }}" method="POST" class="space-y-4">
            @csrf

            @foreach($quiz->questions as $index => $question)
            @php
                $submittedAnswer = $result['submitted_answers'][$question->id] ?? null;
                $isCorrect       = false;
                if ($result && $submittedAnswer !== null) {
                    if ($submittedAnswer == $question->correct_answer) {
                        $isCorrect = true;
                    } elseif (is_array($question->options) && isset($question->options[$submittedAnswer]) && $question->options[$submittedAnswer] === $question->correct_answer) {
                        $isCorrect = true;
                    }
                }
                $isWrong         = $result && !$isCorrect && $question->type === 'multiple_choice';
            @endphp

            <div class="group bg-white dark:bg-slate-900 rounded-2xl border transition-all
                @if($result && $question->type === 'multiple_choice')
                    {{ $isCorrect ? 'border-emerald-200 dark:border-emerald-800/70' : 'border-rose-200 dark:border-rose-800/70' }}
                @else
                    border-slate-200 dark:border-slate-800
                @endif">

                {{-- Header soal --}}
                <div class="flex items-center justify-between px-5 pt-5 pb-3">
                    <div class="flex items-center gap-2">
                        {{-- Nomor besar --}}
                        <span class="w-7 h-7 rounded-lg text-xs font-bold flex items-center justify-center shrink-0
                            @if($result && $question->type === 'multiple_choice')
                                {{ $isCorrect ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400' : 'bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400' }}
                            @else
                                bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400
                            @endif">
                            {{ $index + 1 }}
                        </span>
                        @if($question->type === 'essay')
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400">Essay</span>
                        @endif
                    </div>
                    @if($result && $question->type === 'multiple_choice')
                        <span class="text-xs font-bold
                            {{ $isCorrect ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-500 dark:text-rose-400' }}">
                            {{ $isCorrect ? '✓ Benar' : '✗ Salah' }}
                        </span>
                    @endif
                </div>

                {{-- Teks pertanyaan --}}
                <p class="px-5 pb-4 text-[14px] font-semibold text-slate-900 dark:text-white leading-relaxed">
                    {{ $question->question }}
                </p>

                {{-- Pilihan Ganda --}}
                @if($question->type === 'multiple_choice')
                <div class="px-3 pb-3 space-y-1.5">
                    @if(is_array($question->options))
                        @foreach($question->options as $key => $option)
                        @php
                            $answersList = $result['submitted_answers'] ?? [];
                            $submittedAnswer = null;
                            foreach($answersList as $qId => $ans) {
                                if ((string)$qId === (string)$question->id) {
                                    $submittedAnswer = $ans;
                                    break;
                                }
                            }
                            $isSelected = $submittedAnswer !== null && (string)$submittedAnswer === (string)$key;
                            $isAnswer   = ((string)$question->correct_answer === (string)$key) || ((string)$question->correct_answer === (string)$option);
                        @endphp
                        <label class="flex items-center gap-3 px-3.5 py-3 rounded-xl border text-sm cursor-pointer transition-all select-none
                            @if($result)
                                @if($isAnswer)         border-emerald-300 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-800 dark:text-emerald-300
                                @elseif($isSelected)   border-rose-300 dark:border-rose-700 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-300
                                @else                  border-slate-100 dark:border-slate-800 text-slate-500 dark:text-slate-500
                                @endif
                            @else
                                border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300
                                hover:border-indigo-300 dark:hover:border-indigo-600 hover:bg-indigo-50/60 dark:hover:bg-indigo-900/20
                                has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50 dark:has-[:checked]:bg-indigo-900/30
                            @endif">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $key }}"
                                   {{ $result ? 'disabled' : 'required' }}
                                   {{ $isSelected ? 'checked' : '' }}
                                   class="text-indigo-600 border-slate-300 dark:border-slate-600 focus:ring-indigo-500 shrink-0">
                            <span class="font-bold w-4 shrink-0
                                @if($result)
                                    @if($isAnswer) text-emerald-600 dark:text-emerald-400
                                    @elseif($isSelected) text-rose-500
                                    @else text-slate-300 dark:text-slate-600
                                    @endif
                                @else text-slate-400 @endif">
                                {{ $key }}.
                            </span>
                            <span class="flex-1 leading-snug">{{ $option }}</span>
                            @if($result && $isAnswer)
                                <svg class="w-4 h-4 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($result && $isSelected && !$isAnswer)
                                <svg class="w-4 h-4 text-rose-400 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </label>
                        @endforeach
                    @else
                        <p class="text-xs text-rose-500 px-2">Opsi tidak tersedia.</p>
                    @endif
                </div>

                {{-- Essay --}}
                @else
                <div class="px-5 pb-5">
                    <textarea name="answers[{{ $question->id }}]"
                              rows="4"
                              {{ $result ? 'disabled' : '' }}
                              class="w-full border border-slate-200 dark:border-slate-700 rounded-xl p-3.5 text-sm text-slate-900 dark:text-slate-100
                                     bg-slate-50 dark:bg-slate-800/60 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent
                                     resize-none placeholder:text-slate-400 disabled:opacity-70 disabled:cursor-default"
                              placeholder="Tulis jawaban essay kamu di sini...">{{ $result ? ($result['submitted_answers'][$question->id] ?? '') : '' }}</textarea>
                </div>
                @endif

                {{-- Pembahasan toggle --}}
                @if($result && $question->explanation)
                <div x-show="showPembahasan" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="mx-3 mb-3 p-4 rounded-xl border border-dashed border-indigo-200 dark:border-indigo-800 bg-indigo-50/60 dark:bg-indigo-900/10">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-500 dark:text-indigo-400 mb-1.5">Pembahasan</p>
                    <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">{{ $question->explanation }}</p>
                </div>
                @endif
            </div>
            @endforeach

            {{-- Tombol Submit sticky (hanya saat belum submit) --}}
            @if(!$result)
            <div class="sticky bottom-4 pt-2">
                <button type="submit"
                        class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 active:scale-[0.99] text-white rounded-2xl font-bold text-sm transition-all
                               shadow-lg shadow-indigo-200 dark:shadow-indigo-900/40 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kumpulkan Jawaban
                </button>
            </div>
            @endif
        </form>

        {{-- Tombol kembali di bawah setelah selesai --}}
        @if($result)
        <div class="mt-6 text-center">
            <a href="{{ $backUrl }}"
               class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 dark:text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke {{ (request('from') === 'subject' || $quiz->type === 'admin') ? 'Mata Kuliah' : 'Latihan' }}
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
