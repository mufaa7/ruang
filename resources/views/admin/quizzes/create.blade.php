@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-bold text-black">Create New Quiz</h2>
        <a href="{{ route('admin.quizzes.index') }}" class="text-slate-600 hover:text-black font-medium text-sm">
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

    <form action="{{ route('admin.quizzes.store') }}" method="POST" class="space-y-6" x-data="quizForm()">
        @csrf

        {{-- Quiz Metadata --}}
        <div class="dev-card p-6">
            <h3 class="text-sm font-bold text-black mb-4 border-b border-black pb-2">Quiz Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full border border-black p-2 text-sm text-black bg-white focus:outline-none focus:ring-0 focus:border-black placeholder:text-slate-400" placeholder="e.g. Midterm Physics">
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1">Subject</label>
                    <select name="subject_id" x-model="selectedSubjectId" required class="w-full border border-black p-2 text-sm text-black bg-white focus:outline-none focus:ring-0 focus:border-black">
                        <option value="">Select Subject...</option>
                        <template x-for="subject in subjectsData" :key="subject.id">
                            <option :value="subject.id" x-text="subject.name"></option>
                        </template>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="selectedSubjectId" x-transition>
                <div>
                    <label class="block text-xs font-bold text-black mb-1">Source Material (Optional)</label>
                    <select name="material_id" x-model="selectedMaterialId" class="w-full border border-black p-2 text-sm text-black bg-white focus:outline-none focus:ring-0 focus:border-black">
                        <option value="">-- No specific material --</option>
                        <template x-for="mat in currentMaterials" :key="mat.id">
                            <option :value="mat.id" x-text="mat.title + ' (oleh ' + (mat.user ? mat.user.name : 'Unknown') + ')'"></option>
                        </template>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-black mb-1">Time Limit (Minutes)</label>
                    <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', 30) }}" min="1" class="w-full border border-black p-2 text-sm text-black bg-white focus:outline-none focus:ring-0 focus:border-black">
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
                        <label class="flex items-center gap-2 cursor-pointer bg-white p-2 border border-slate-200 hover:border-black transition-colors">
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
                <span x-text="showBulk ? '▲' : '▼'" class="text-xs text-black"></span>
            </button>
            <div x-show="showBulk" x-collapse class="p-4 bg-white border-b border-black">
                <p class="text-xs text-slate-600 mb-2 font-medium">Paste text from Word/PDF. Format must include question numbers (1.), options (A., B., C., D.), and optional answer key (Kunci: A).</p>
                <textarea x-model="bulkText" rows="6" class="w-full border border-black p-2 text-xs font-mono text-black bg-white focus:outline-none focus:ring-0 focus:border-black mb-2 placeholder:text-slate-400" placeholder="1. Ibukota Indonesia adalah?&#10;A. Jakarta&#10;B. Bandung&#10;C. Surabaya&#10;D. Bali&#10;Kunci: A"></textarea>
                <button type="button" @click="parseBulk" class="bg-black text-white px-4 py-1.5 text-xs font-bold border border-black hover:bg-slate-800">
                    Parse Text
                </button>
                <p x-show="parseMessage" x-text="parseMessage" class="text-xs mt-2 font-bold text-emerald-600"></p>
            </div>
        </div>

        {{-- Spreadsheet Layout --}}
        <div class="dev-card p-0 overflow-x-auto relative">
            <div class="flex items-center justify-between p-4 bg-white border-b border-black min-w-[800px]">
                <h3 class="text-sm font-bold text-black flex items-center gap-2">Questions (Spreadsheet)</h3>
                <button type="button" @click="addQuestion" class="bg-black text-white px-3 py-1 text-xs font-bold border border-black hover:bg-slate-800">
                    + Add Question
                </button>
            </div>

            <table class="w-full text-left text-sm whitespace-nowrap min-w-[1000px]">
                <thead>
                    <tr class="bg-[#f8f9fa] text-xs uppercase tracking-wider">
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-24">Type</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-56">Question</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt A</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt B</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt C</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black">Opt D</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black text-center w-20">Correct</th>
                        <th class="px-2 py-2 font-bold text-black border-r border-b border-black w-40">Explanation / Key</th>
                        <th class="px-2 py-2 font-bold text-black border-b border-black w-12 text-center">Del</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-black">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <tr class="hover:bg-slate-50 group">
                            <td class="px-0 py-0 border-r border-black">
                                <select :name="`questions[${index}][type]`" x-model="q.type" required class="w-full h-full border-none p-1 text-xs text-black focus:outline-none focus:ring-0 bg-transparent text-center appearance-none font-bold cursor-pointer">
                                    <option value="multiple_choice">Pilgan</option>
                                    <option value="essay">Essay</option>
                                </select>
                            </td>
                            <td class="px-0 py-0 border-r border-black relative group-td">
                                <textarea :name="`questions[${index}][question]`" x-model="q.question" required rows="1" class="w-full h-full p-2 text-xs text-black border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight placeholder:text-slate-400" placeholder="Question"></textarea>
                            </td>
                            <td class="px-0 py-0 border-r border-black" :class="q.type === 'essay' ? 'bg-slate-200' : ''">
                                <textarea :disabled="q.type === 'essay'" :name="`questions[${index}][options][0]`" x-model="q.options[0]" rows="1" class="w-full h-full p-2 text-xs text-black border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight disabled:opacity-50 placeholder:text-slate-400" placeholder="A"></textarea>
                            </td>
                            <td class="px-0 py-0 border-r border-black" :class="q.type === 'essay' ? 'bg-slate-200' : ''">
                                <textarea :disabled="q.type === 'essay'" :name="`questions[${index}][options][1]`" x-model="q.options[1]" rows="1" class="w-full h-full p-2 text-xs text-black border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight disabled:opacity-50 placeholder:text-slate-400" placeholder="B"></textarea>
                            </td>
                            <td class="px-0 py-0 border-r border-black" :class="q.type === 'essay' ? 'bg-slate-200' : ''">
                                <textarea :disabled="q.type === 'essay'" :name="`questions[${index}][options][2]`" x-model="q.options[2]" rows="1" class="w-full h-full p-2 text-xs text-black border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight disabled:opacity-50 placeholder:text-slate-400" placeholder="C"></textarea>
                            </td>
                            <td class="px-0 py-0 border-r border-black" :class="q.type === 'essay' ? 'bg-slate-200' : ''">
                                <textarea :disabled="q.type === 'essay'" :name="`questions[${index}][options][3]`" x-model="q.options[3]" rows="1" class="w-full h-full p-2 text-xs text-black border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight disabled:opacity-50 placeholder:text-slate-400" placeholder="D"></textarea>
                            </td>
                            <td class="px-2 py-1 text-center border-r border-black" :class="q.type === 'essay' ? 'bg-slate-200' : ''">
                                <select :disabled="q.type === 'essay'" :name="`questions[${index}][correct_answer]`" x-model="q.correct_answer" class="w-full border border-black p-1 text-xs text-black focus:outline-none focus:ring-0 focus:border-black bg-white font-bold text-center appearance-none disabled:opacity-50">
                                    <option value="0">A</option>
                                    <option value="1">B</option>
                                    <option value="2">C</option>
                                    <option value="3">D</option>
                                </select>
                            </td>
                            <td class="px-0 py-0 border-r border-black">
                                <textarea :name="`questions[${index}][explanation]`" x-model="q.explanation" rows="1" class="w-full h-full p-2 text-xs text-black border-none focus:ring-0 focus:outline-none resize-none bg-transparent m-0 overflow-hidden leading-tight placeholder:text-slate-400" :placeholder="q.type === 'essay' ? 'Kunci Jawaban/Rubrik' : 'Optional exp...'"></textarea>
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
            <div x-show="questions.length === 0" class="text-center text-slate-500 py-6 font-medium text-sm">
                No questions added yet. Click "+ Add Question" or paste from Bulk Magic.
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-emerald-600 text-white px-6 py-2 text-sm font-bold border border-black hover:bg-emerald-700 transition-colors">
                Save Quiz
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function quizForm() {
        return {
            subjectsData: @json($subjects),
            selectedSubjectId: '{{ old('subject_id', $prefillMaterial?->subject_id) }}',
            selectedMaterialId: '{{ old('material_id', $prefillMaterial?->id) }}',
            selectedUsers: {!! json_encode(old('target_users', $prefillMaterial ? [$prefillMaterial->user_id] : [])) !!},
            get currentMaterials() {
                if (!this.selectedSubjectId) return [];
                const subj = this.subjectsData.find(s => s.id == this.selectedSubjectId);
                return subj ? subj.materials : [];
            },
            get currentUsers() {
                if (!this.selectedSubjectId) return [];
                const subj = this.subjectsData.find(s => s.id == this.selectedSubjectId);
                return subj ? subj.users : [];
            },
            showBulk: false,
            bulkText: '',
            parseMessage: '',
            questions: Array.from({ length: 10 }).map((_, i) => ({
                id: Date.now() + i,
                type: 'multiple_choice',
                question: '',
                options: ['', '', '', ''],
                correct_answer: 0,
                explanation: ''
            })),
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
            parseBulk() {
                if (!this.bulkText.trim()) return;

                const text = this.bulkText;
                
                const blockRegex = /(?:\n|^)\s*\d+[\.\)]\s*(.*?)(?=(?:\n\s*\d+[\.\)]\s*)|$)/gs;
                
                let match;
                let parsedCount = 0;
                let newQuestions = [];

                while ((match = blockRegex.exec(text)) !== null) {
                    let block = match[1].trim();
                    
                    const optARegex = /\n?\s*[aA][\.\)]\s*(.*?)(?=\n?\s*[bB][\.\)]|$)/s;
                    const optBRegex = /\n?\s*[bB][\.\)]\s*(.*?)(?=\n?\s*[cC][\.\)]|$)/s;
                    const optCRegex = /\n?\s*[cC][\.\)]\s*(.*?)(?=\n?\s*[dD][\.\)]|$)/s;
                    const optDRegex = /\n?\s*[dD][\.\)]\s*(.*?)(?=\n?\s*(?:Kunci|Jawaban|Ans|Answer)\s*[\:\.]|$)/is;
                    const ansRegex = /\n?\s*(?:Kunci|Jawaban|Ans|Answer)\s*[\:\.]\s*([a-dA-D])/i;

                    let qText = block.split(/\n?\s*[aA][\.\)]/)[0].trim();
                    
                    let aMatch = block.match(optARegex);
                    let bMatch = block.match(optBRegex);
                    let cMatch = block.match(optCRegex);
                    let dMatch = block.match(optDRegex);
                    let ansMatch = block.match(ansRegex);

                    let type = 'multiple_choice';
                    let options = ['', '', '', ''];
                    let correctAnswer = 0;

                    if (aMatch || bMatch || cMatch || dMatch) {
                        options = [
                            aMatch ? aMatch[1].trim() : '',
                            bMatch ? bMatch[1].trim() : '',
                            cMatch ? cMatch[1].trim() : '',
                            dMatch ? dMatch[1].trim() : ''
                        ];
                        if (ansMatch) {
                            let letter = ansMatch[1].toUpperCase();
                            correctAnswer = letter === 'A' ? 0 : (letter === 'B' ? 1 : (letter === 'C' ? 2 : 3));
                        }
                    } else {
                        type = 'essay';
                    }

                    let explanationText = '';
                    if (type === 'essay' && ansMatch) {
                        explanationText = ansMatch[1].trim();
                    }

                    const essayAnsRegex = /\n?\s*(?:Kunci|Jawaban|Ans|Answer)\s*[\:\.]\s*(.*)/is;
                    let essayMatch = block.match(essayAnsRegex);
                    if (type === 'essay' && essayMatch) {
                        explanationText = essayMatch[1].trim();
                        qText = block.split(/\n?\s*(?:Kunci|Jawaban|Ans|Answer)\s*[\:\.]/)[0].trim();
                    }

                    newQuestions.push({
                        id: Date.now() + Math.random(),
                        type: type,
                        question: qText,
                        options: options,
                        correct_answer: correctAnswer,
                        explanation: explanationText
                    });
                    parsedCount++;
                }

                if (parsedCount > 0) {
                    const isEmpty = this.questions.every(q => !q.question && q.options.every(o => !o));
                    if (isEmpty) {
                        this.questions = newQuestions;
                    } else {
                        this.questions = [...this.questions, ...newQuestions];
                    }
                    this.parseMessage = `Successfully parsed ${parsedCount} questions!`;
                    this.bulkText = '';
                    
                    setTimeout(() => { this.parseMessage = ''; this.showBulk = false; }, 3000);
                } else {
                    this.parseMessage = 'Could not detect any valid questions. Please check the format.';
                }
            }
        }
    }
</script>
@endpush
@endsection