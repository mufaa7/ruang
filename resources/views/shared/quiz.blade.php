<x-app-layout>
    <x-slot name="pageTitle">{{ $quiz->title }}</x-slot>

    @php
        // $result sudah di-pass dari controller (null jika belum pernah dikerjakan)
        $score = $result['score'] ?? null;
        $total   = $quiz->questions->count();
        $mcqCount = $quiz->questions->where('type','multiple_choice')->count();

        // Pesan random berdasarkan skor
        $msgs = [
            100 => ['ph-trophy', 'Sempurna!', 'Nilai lu memuaskan, pertahankan (kalo bisa).'],
            80  => ['ph-fire', 'Luar Biasa!', 'Dikit lagi nyempurnain. Tetep keren sih.'],
            60  => ['ph-sneezing', 'Lumayan.', 'Masih bisa lebih baik. Coba lagi abis ngopi.'],
            40  => ['ph-skull', 'Aduh.', 'Belajar lagi ya. Pembahasan di bawah tuh gratis lho.'],
            0   => ['ph-face-palm', 'Serius?', 'Ini kuis apa random klik? Buka catatan dulu kali.'],
        ];
        $msgKey = 0;
        foreach ([100,80,60,40,0] as $threshold) {
            if ($score >= $threshold) { $msgKey = $threshold; break; }
        }
        [$msgIcon, $msgTitle, $msgBody] = $msgs[$msgKey];
    @endphp

    <div x-data="{ showPembahasan: {{ $result ? 'false' : 'false' }} }" class="max-w-2xl mx-auto pb-20 animate-fadeIn">

        {{-- ── Banner Header ── --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <div>
                <a href="{{ $backUrl }}" class="inline-flex items-center text-sm font-medium text-slate-400 hover:text-white transition-colors mb-2">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Cabut Dulu
                </a>
                <h1 class="text-2xl font-bold text-white font-geist flex items-center gap-2">
                    <i class="ph ph-notepad text-[1.1em] align-middle text-emerald-400"></i> {{ $quiz->title }}
                </h1>
                <p class="text-sm text-slate-400 font-medium mt-1">
                    {{ $quiz->subject->name ?? 'Kuis' }} · {{ $total }} soal
                    @if($quiz->time_limit_minutes) · {{ $quiz->time_limit_minutes }} menit @endif
                    @if($result) · <span class="text-emerald-400 font-bold">Mode Review</span> @endif
                </p>
            </div>
            @if($result)
            {{-- Skor badge di kanan --}}
            <div class="shrink-0 text-center bg-white/5 backdrop-blur-xl p-3 rounded-2xl border border-white/10 shadow-sm">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center font-bold text-2xl
                    {{ $score >= 70 ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30' : 'bg-rose-500/20 text-rose-400 border border-rose-500/30' }}">
                    {{ $score }}
                </div>
                <p class="text-[10px] text-slate-400 font-bold mt-2 uppercase tracking-wide">Nilai</p>
            </div>
            @endif
        </div>

        {{-- ── Hasil Panel ── --}}
        @if($result)
        <div class="mb-8 rounded-2xl border {{ $score >= 70 ? 'border-emerald-500/30 bg-emerald-500/10' : 'border-rose-500/30 bg-rose-500/10' }} p-5 backdrop-blur-xl">
            <div class="flex items-center gap-4">
                <div class="text-4xl {{ $score >= 70 ? 'text-emerald-400' : 'text-rose-400' }}">
                    <i class="ph {{ $msgIcon }}"></i>
                </div>
                <div>
                    <p class="font-bold text-white text-lg">{{ $msgTitle }}</p>
                    <p class="text-sm text-slate-300 mt-0.5">{{ $msgBody }}</p>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t {{ $score >= 70 ? 'border-emerald-500/30' : 'border-rose-500/30' }} flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <p class="text-sm font-semibold text-slate-300">
                    Benar <span class="font-bold text-white">{{ $result['correct'] }}/{{ $result['total'] }}</span>
                    soal PG
                    @if($quiz->questions->where('type','essay')->count() > 0)
                        <span class="text-slate-400 font-normal">&nbsp;· essay belum dihitung</span>
                    @endif
                </p>
                <div class="flex gap-2">
                    <button @click="showPembahasan = !showPembahasan"
                            class="text-xs font-bold px-3 py-1.5 rounded-lg border transition-colors
                                {{ $score >= 70
                                    ? 'border-emerald-500/30 text-emerald-400 hover:bg-emerald-500/20'
                                    : 'border-rose-500/30 text-rose-400 hover:bg-rose-500/20' }}">
                        <span x-text="showPembahasan ? 'Sembunyikan' : 'Lihat Pembahasan'"></span>
                    </button>
                    <a href="{{ $backUrl }}"
                       class="text-xs font-bold px-3 py-1.5 rounded-lg border border-white/10 text-slate-300 hover:bg-white/10 hover:text-white transition-colors">
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

            <div class="group bg-white/5 backdrop-blur-xl rounded-[24px] border transition-all shadow-sm
                @if($result && $question->type === 'multiple_choice')
                    {{ $isCorrect ? 'border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.05)]' : 'border-rose-500/30 shadow-[0_0_15px_rgba(244,63,94,0.05)]' }}
                @else
                    border-white/10
                @endif">

                {{-- Header soal --}}
                <div class="flex items-center justify-between px-6 pt-6 pb-4">
                    <div class="flex items-center gap-3">
                        {{-- Nomor besar --}}
                        <span class="w-8 h-8 rounded-xl text-sm font-bold flex items-center justify-center shrink-0 border border-white/5
                            @if($result && $question->type === 'multiple_choice')
                                {{ $isCorrect ? 'bg-emerald-500/20 text-emerald-400' : 'bg-rose-500/20 text-rose-400' }}
                            @else
                                bg-white/10 text-white
                            @endif">
                            {{ $index + 1 }}
                        </span>
                        @if($question->type === 'essay')
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md bg-amber-500/20 text-amber-400 border border-amber-500/30">Essay</span>
                        @endif
                    </div>
                    @if($result && $question->type === 'multiple_choice')
                        <span class="text-xs font-bold px-2.5 py-1 rounded-md border
                            {{ $isCorrect ? 'text-emerald-400 border-emerald-500/20 bg-emerald-500/10' : 'text-rose-400 border-rose-500/20 bg-rose-500/10' }}">
                            {{ $isCorrect ? '✔ Benar' : '✘ Salah' }}
                        </span>
                    @endif
                </div>

                {{-- Teks pertanyaan --}}
                <p class="px-6 pb-5 text-[15px] font-semibold text-white leading-relaxed">
                    {{ $question->question }}
                </p>

                {{-- Pilihan Ganda --}}
                @if($question->type === 'multiple_choice')
                <div class="px-4 pb-4 space-y-2">
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
                        <label class="flex items-center gap-3 px-4 py-3.5 rounded-xl border text-[14px] cursor-pointer transition-all select-none group/label
                            @if($result)
                                @if($isAnswer)         border-emerald-500/50 bg-emerald-500/20 text-emerald-100 shadow-[0_0_10px_rgba(16,185,129,0.1)]
                                @elseif($isSelected)   border-rose-500/50 bg-rose-500/20 text-rose-100
                                @else                  border-white/5 bg-transparent text-slate-400 opacity-60
                                @endif
                            @else
                                border-white/5 bg-white/5 text-slate-300
                                hover:border-white/20 hover:bg-white/10 hover:text-white
                                has-[:checked]:border-white/30 has-[:checked]:bg-white/15 has-[:checked]:text-white
                            @endif">
                            <input type="radio"
                                   name="answers[{{ $question->id }}]"
                                   value="{{ $key }}"
                                   {{ $result ? 'disabled' : 'required' }}
                                   {{ $isSelected ? 'checked' : '' }}
                                   class="text-neutral-900 border-white/20 bg-transparent focus:ring-1 focus:ring-white/30 focus:ring-offset-0 focus:ring-offset-transparent shrink-0">
                            <span class="font-bold w-5 shrink-0 text-center
                                @if($result)
                                    @if($isAnswer) text-emerald-400
                                    @elseif($isSelected) text-rose-400
                                    @else text-slate-500
                                    @endif
                                @else text-slate-400 group-has-[:checked]/label:text-white @endif">
                                {{ $key }}
                            </span>
                            <span class="flex-1 leading-snug">{{ $option }}</span>
                            @if($result && $isAnswer)
                                <svg class="w-5 h-5 text-emerald-400 shrink-0 drop-shadow-[0_0_4px_rgba(16,185,129,0.5)]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($result && $isSelected && !$isAnswer)
                                <svg class="w-5 h-5 text-rose-400 shrink-0 drop-shadow-[0_0_4px_rgba(244,63,94,0.5)]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </label>
                        @endforeach
                    @else
                        <p class="text-xs text-rose-400 px-2">Opsi tidak tersedia.</p>
                    @endif
                </div>

                {{-- Essay --}}
                @else
                <div class="px-5 pb-5">
                    <textarea name="answers[{{ $question->id }}]"
                              rows="4"
                              {{ $result ? 'disabled' : '' }}
                              class="w-full border border-white/10 rounded-xl p-4 text-[14px] text-white
                                     bg-white/5 focus:outline-none focus:ring-2 focus:ring-white/20 focus:border-white/20
                                     resize-none placeholder:text-slate-500 disabled:opacity-50 disabled:cursor-default"
                              placeholder="Tulis jawaban essay kamu di sini...">{{ $result ? ($result['submitted_answers'][$question->id] ?? '') : '' }}</textarea>
                </div>
                @endif

                {{-- Pembahasan toggle --}}
                @if($result && $question->explanation)
                <div x-show="showPembahasan" x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     class="mx-4 mb-4 p-5 rounded-xl border border-white/10 bg-white/5">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-400 mb-2 flex items-center gap-1">
                        <i class="ph-fill ph-lightbulb"></i> Pembahasan
                    </p>
                    <p class="text-[14px] text-slate-300 leading-relaxed">{{ $question->explanation }}</p>
                </div>
                @endif
            </div>
            @endforeach

            {{-- Tombol Submit sticky (hanya saat belum submit) --}}
            @if(!$result)
            <div class="sticky bottom-4 pt-4 z-10">
                <button type="submit"
                        class="w-full py-4 bg-white hover:bg-neutral-200 active:scale-[0.98] text-black rounded-2xl font-bold text-[15px] transition-all
                               shadow-xl flex items-center justify-center gap-2 group border border-white/20">
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                    Kumpulkan Jawaban
                </button>
            </div>
            @endif
        </form>

        {{-- Tombol kembali di bawah setelah selesai --}}
        @if($result)
        <div class="mt-8 text-center">
            <a href="{{ $backUrl }}"
               class="inline-flex items-center gap-2 text-[14px] font-semibold text-slate-400 hover:text-white transition-colors py-2 px-4 rounded-xl border border-transparent hover:border-white/10 hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke {{ (request('from') === 'subject' || $quiz->type === 'admin') ? 'Mata Kuliah' : 'Latihan' }}
            </a>
        </div>
        @endif
    </div>
</x-app-layout>
