@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">Create New Flashcard Set</h2>
        <a href="{{ route('admin.flashcards.index') }}" class="text-slate-600 hover:text-black font-medium text-sm">
            ← Back to List
        </a>
    </div>

    @if ($errors->any())
        <div class="bg-rose-50 border border-rose-500 text-rose-700 px-4 py-3 text-sm font-bold">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.flashcards.store') }}" method="POST" class="space-y-6" x-data="flashcardForm()">
        @csrf
        @if(isset($prefillMaterial))
            <input type="hidden" name="material_id" value="{{ $prefillMaterial->id }}">
        @endif

        {{-- Metadata --}}
        <div class="dev-card p-6">
            <h3 class="text-sm font-bold text-black mb-4 border-b border-black pb-2">Flashcard Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-black p-2 text-sm focus:outline-none focus:ring-0 focus:border-black" placeholder="e.g. Vocabulary Set 1">
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1">Subject</label>
                    <select name="subject_id" x-model="selectedSubjectId" required class="w-full border border-black p-2 text-sm focus:outline-none focus:ring-0 focus:border-black bg-white dark:bg-slate-900">
                        <option value="">Select Subject...</option>
                        <template x-for="subject in subjectsData" :key="subject.id">
                            <option :value="subject.id" x-text="subject.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="mt-6" x-show="selectedSubjectId" x-transition>
                <label class="block text-xs font-bold text-black mb-2 border-b border-slate-200 pb-1">Target Users (Siapa yang bisa lihat?)</label>
                <div class="text-[11px] text-slate-500 mb-2">Leave all unchecked to make it public for this subject, or select specific users.</div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 max-h-40 overflow-y-auto p-2 border border-black bg-[#f8f9fa]">
                    <template x-if="currentUsers.length === 0">
                        <div class="text-xs text-slate-500 italic col-span-full">No users enrolled in this subject.</div>
                    </template>
                    <template x-for="user in currentUsers" :key="user.id">
                        <label class="flex items-center gap-2 cursor-pointer bg-white p-2 border border-slate-200 hover:border-black transition-colors dark:bg-slate-900">
                            <input type="checkbox" name="target_users[]" :value="user.id" x-model="selectedUsers" class="text-black border-slate-300 focus:ring-black">
                            <span class="text-xs font-bold text-black" x-text="user.name"></span>
                        </label>
                    </template>
                </div>
            </div>
        </div>

        {{-- Bulk Paste Section --}}
        <div class="dev-card p-0 overflow-hidden">
            <button type="button" @click="showBulk = !showBulk" class="w-full flex items-center justify-between p-4 bg-[#f8f9fa] border-b border-black hover:bg-slate-50">
                <span class="text-sm font-bold text-black">⚡ Bulk Paste Magic</span>
                <span x-text="showBulk ? '▲' : '▼'" class="text-xs"></span>
            </button>
            <div x-show="showBulk" x-collapse class="p-4 bg-white border-b border-black dark:bg-slate-900">
                <p class="text-xs text-slate-600 mb-2 font-medium">Paste text here. Use a colon (:) to separate Front and Back. E.g., <span class="bg-slate-100 px-1">Gravity: The force that attracts a body toward the center of the earth.</span></p>
                <textarea x-model="bulkText" rows="6" class="w-full border border-black p-2 text-xs font-mono focus:outline-none focus:ring-0 focus:border-black mb-2" placeholder="Photosynthesis: Process by which plants use sunlight to synthesize foods.&#10;Mitochondria: Powerhouse of the cell."></textarea>
                <button type="button" @click="parseBulk" class="bg-black text-white px-4 py-1.5 text-xs font-bold border border-black hover:bg-slate-800">
                    Parse Text
                </button>
                <p x-show="parseMessage" x-text="parseMessage" class="text-xs mt-2 font-bold text-emerald-600"></p>
            </div>
        </div>

        {{-- Spreadsheet Layout --}}
        <div class="dev-card p-0 overflow-x-auto relative">
            <div class="flex items-center justify-between p-4 bg-white border-b border-black min-w-[600px] dark:bg-slate-900">
                <h3 class="text-sm font-bold text-black flex items-center gap-2">Cards (Spreadsheet)</h3>
                <button type="button" @click="addCard" class="bg-black text-white px-3 py-1 text-xs font-bold border border-black hover:bg-slate-800">
                    + Add Card
                </button>
            </div>

            <table class="w-full text-left text-sm whitespace-nowrap min-w-[600px]">
                <thead>
                    <tr class="bg-[#f8f9fa] text-xs uppercase tracking-wider">
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-10 text-center">No</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-1/3">Front (Term/Question)</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-1/2">Back (Definition/Answer)</th>
                        <th class="px-2 py-2 font-bold text-black border-b border-black w-12 text-center">Del</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black">
                    <template x-for="(card, index) in cards" :key="card.id">
                        <tr class="hover:bg-slate-50 group">
                            <td class="px-2 py-1 text-center font-bold text-xs border-r border-black" x-text="index + 1"></td>
                            <td class="px-0 py-0 border-r border-black">
                                <textarea :name="`flashcards[${index}][front]`" x-model="card.front" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight" placeholder="Front..."></textarea>
                            </td>
                            <td class="px-0 py-0 border-r border-black relative group-td">
                                <textarea :name="`flashcards[${index}][back]`" x-model="card.back" class="w-full h-full border-none p-2 text-xs focus:outline-none focus:ring-0 bg-transparent resize-none leading-relaxed" rows="2" placeholder="Definisi..."></textarea>
                            </td>
                            <td class="px-1 py-1 text-center align-middle">
                                <button type="button" @click="removeCard(index)" class="text-rose-600 hover:text-stone-600 font-bold text-xs px-2 py-1 hover:bg-rose-50 rounded" title="Hapus baris">
                                    ✕
                                </button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div x-show="cards.length === 0" class="text-center text-slate-500 py-6 font-medium text-sm">
                No cards added yet. Click "+ Add Card" or paste from Bulk Magic.
            </div>
        </div>

        {{-- Quiz Builder --}}
        <div class="dev-card p-6">
            <div class="flex items-center justify-between mb-4 border-b border-black pb-2">
                <div>
                    <h3 class="text-sm font-bold text-black flex items-center gap-2">
                        <svg class="w-4 h-4 text-neutral-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Kuis
                    </h3>
                    <p class="text-xs text-slate-500 mt-1">Buat soal-soal kuis pilihan ganda yang akan muncul setelah flashcard tamat. Biarkan kosong jika tidak ingin ada kuis.</p>
                </div>
            </div>

            <div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-black mb-1">Judul Kuis (Opsional)</label>
                    <input type="text" name="quiz_title" x-model="quizTitle" class="w-full border border-black p-2 text-sm focus:outline-none focus:ring-0 focus:border-black" placeholder="Biarkan kosong untuk judul otomatis: 'Kuis: [Judul Flashcard]'">
                </div>

                {{-- Quiz Spreadsheet Layout --}}
                <div class="border border-black overflow-x-auto relative mt-4">
                    <div class="flex items-center justify-between p-4 bg-[#f8f9fa] border-b border-black min-w-[800px]">
                        <h3 class="text-sm font-bold text-black flex items-center gap-2">Questions (Spreadsheet)</h3>
                        <button type="button" @click="addQuestion" class="bg-black text-white px-3 py-1 text-xs font-bold border border-black hover:bg-slate-800">
                            + Add Question
                        </button>
                    </div>

                    <table class="w-full text-left text-sm whitespace-nowrap min-w-[1000px]">
                        <thead>
                            <tr class="bg-slate-100 text-xs uppercase tracking-wider">
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-56">Question</th>
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt A</th>
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt B</th>
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt C</th>
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt D</th>
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black text-center w-20">Correct</th>
                                <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-40">Explanation</th>
                                <th class="px-2 py-2 font-bold text-black border-b border-black w-12 text-center">Del</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-black bg-white dark:bg-slate-900">
                            <template x-for="(q, index) in questions" :key="q.id">
                                <tr class="hover:bg-slate-50 group">
                                    <input type="hidden" :name="`questions[${index}][type]`" value="multiple_choice">
                                    <td class="px-0 py-0 border-r border-black relative group-td">
                                        <textarea :name="`questions[${index}][question]`" x-model="q.question" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight" placeholder="Question text..."></textarea>
                                    </td>
                                    <td class="px-0 py-0 border-r border-black">
                                        <textarea :name="`questions[${index}][options][0]`" x-model="q.options[0]" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight" placeholder="A"></textarea>
                                    </td>
                                    <td class="px-0 py-0 border-r border-black">
                                        <textarea :name="`questions[${index}][options][1]`" x-model="q.options[1]" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight" placeholder="B"></textarea>
                                    </td>
                                    <td class="px-0 py-0 border-r border-black">
                                        <textarea :name="`questions[${index}][options][2]`" x-model="q.options[2]" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight" placeholder="C"></textarea>
                                    </td>
                                    <td class="px-0 py-0 border-r border-black">
                                        <textarea :name="`questions[${index}][options][3]`" x-model="q.options[3]" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight" placeholder="D"></textarea>
                                    </td>
                                    <td class="px-2 py-1 text-center border-r border-black">
                                        <select :name="`questions[${index}][correct_answer]`" x-model="q.correct_answer" class="w-full border border-black p-1 text-xs focus:outline-none focus:ring-0 focus:border-black bg-white font-bold text-center appearance-none dark:bg-slate-900">
                                            <option value="0">A</option>
                                            <option value="1">B</option>
                                            <option value="2">C</option>
                                            <option value="3">D</option>
                                        </select>
                                    </td>
                                    <td class="px-0 py-0 border-r border-black">
                                        <textarea :name="`questions[${index}][explanation]`" x-model="q.explanation" rows="1" class="w-full h-full p-2 text-xs border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight text-slate-500" placeholder="Optional exp..."></textarea>
                                    </td>
                                    <td class="px-1 py-1 text-center align-middle">
                                        <button type="button" @click="removeQuestion(index)" class="text-rose-600 hover:text-stone-600 font-bold text-xs px-2 py-1 hover:bg-rose-50 rounded" title="Hapus soal">
                                            ✕
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <div x-show="questions.length === 0" class="text-center text-slate-500 py-6 font-medium text-sm bg-white dark:bg-slate-900">
                        No quiz questions added. Click "+ Add Question".
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 text-sm font-bold border border-black hover:bg-emerald-700 transition-colors">
                Save Flashcard Set
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function flashcardForm() {
        return {
            subjectsData: @json($subjects),
            selectedSubjectId: '{{ old('subject_id', $prefillMaterial?->subject_id) }}',
            selectedUsers: {!! json_encode(old('target_users', $prefillMaterial ? [$prefillMaterial->user_id] : [])) !!},
            get currentUsers() {
                if (!this.selectedSubjectId) return [];
                const subj = this.subjectsData.find(s => s.id == this.selectedSubjectId);
                return subj ? subj.users : [];
            },
            showBulk: false,
            bulkText: '',
            parseMessage: '',
            cards: Array.from({ length: 10 }).map((_, i) => ({
                id: Date.now() + i,
                front: '',
                back: ''
            })),
            enableQuiz: false,
            quizTitle: '',
            questions: [
                {
                    id: Date.now(),
                    type: 'multiple_choice',
                    question: '',
                    options: ['', '', '', ''],
                    correct_answer: 0,
                    explanation: ''
                }
            ],
            addQuestion() {
                this.questions.push({
                    id: Date.now(),
                    type: 'multiple_choice',
                    question: '',
                    options: ['', '', '', ''],
                    correct_answer: 0,
                    explanation: ''
                });
            },
            removeQuestion(index) {
                this.questions.splice(index, 1);
            },
            addCard() {
                this.cards.push({
                    id: Date.now(),
                    front: '',
                    back: ''
                });
            },
            removeCard(index) {
                this.cards.splice(index, 1);
            },
            parseBulk() {
                if (!this.bulkText.trim()) return;

                const text = this.bulkText;
                const lines = text.split('\n');
                
                let parsedCount = 0;
                let newCards = [];

                lines.forEach(line => {
                    const parts = line.split(/:(.*)/s); // Split on first colon
                    if (parts.length >= 2) {
                        const front = parts[0].trim();
                        const back = parts[1].trim();
                        if (front || back) {
                            newCards.push({
                                id: Date.now() + Math.random(),
                                front: front,
                                back: back
                            });
                            parsedCount++;
                        }
                    }
                });

                if (parsedCount > 0) {
                    const isEmpty = this.cards.every(c => !c.front && !c.back);
                    if (isEmpty) {
                        this.cards = newCards;
                    } else {
                        this.cards = [...this.cards, ...newCards];
                    }
                    this.parseMessage = `Successfully parsed ${parsedCount} cards!`;
                    this.bulkText = '';
                    
                    setTimeout(() => { this.parseMessage = ''; this.showBulk = false; }, 3000);
                } else {
                    this.parseMessage = 'Could not detect any valid cards. Please ensure each line uses a colon (Front : Back).';
                }
            }
        }
    }
</script>
@endpush
@endsection