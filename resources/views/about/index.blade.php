<x-app-layout>
    <x-slot name="pageTitle">Tentang Ruang</x-slot>

    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=Lora:ital,wght@0,400;0,500;1,400&display=swap" rel="stylesheet">
    <style>
        .font-instrument { font-family: 'Instrument Serif', serif; }
        .font-lora { font-family: 'Lora', serif; }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade {
            opacity: 0;
            animation: fadeInUp 1.2s ease-out forwards;
        }
        
        /* Subtle paper noise texture */
        .bg-paper {
            background-color: #faf8f3;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.03'/%3E%3C/svg%3E");
        }
        
        .dark .bg-paper {
            background-color: #0f1115;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)' opacity='0.02'/%3E%3C/svg%3E");
        }
    </style>
    @endpush

    {{-- Paper Document Wrapper --}}
    <div class="max-w-3xl mx-auto min-h-screen pb-12 sm:pb-24">
        
        <div class="bg-paper rounded-[32px] sm:rounded-[48px] shadow-sm border border-neutral-200/50 dark:border-neutral-800/50 relative overflow-hidden transition-colors duration-500">
            
            {{-- Easter Egg (Top Right) --}}
            <div class="absolute top-6 right-6 sm:top-10 sm:right-10 text-[10px] sm:text-[11px] font-mono text-neutral-400 dark:text-neutral-600 group cursor-default transition-all duration-300 select-none z-10">
                <span class="group-hover:hidden">version 0.1</span>
                <span class="hidden group-hover:inline text-neutral-500 dark:text-neutral-400">for someone.</span>
            </div>

            {{-- Content --}}
            <div class="px-6 py-12 sm:px-16 sm:py-20 lg:px-24 lg:py-24">
                
                {{-- Header --}}
                <div class="mb-10 sm:mb-16 animate-fade" style="animation-delay: 200ms;">
                    <h1 class="text-4xl sm:text-5xl font-light tracking-tight font-instrument text-neutral-900 dark:text-neutral-100 leading-tight">
                        Tentang Ruang
                    </h1>
                    <p class="italic text-neutral-500 dark:text-neutral-400 mt-4 text-base sm:text-lg">
                        Sebuah tempat yang lahir dari janji yang berubah bentuk.
                    </p>
                </div>

                {{-- Spacing adjusted for better reading --}}
                <div class="space-y-6 sm:space-y-8 text-neutral-700 dark:text-neutral-300 leading-relaxed sm:leading-loose text-base sm:text-lg font-lora text-left sm:text-justify tracking-wide">
                    
                    <p class="animate-fade" style="animation-delay: 400ms;">
                        Ada kalanya hidup mengajarkan bahwa tidak semua janji bisa ditepati dengan cara yang pernah kita bayangkan.
                    </p>

                    <p class="animate-fade" style="animation-delay: 600ms;">
                        Kadang yang ingin kita lakukan terdengar begitu sederhana. Duduk di samping seseorang ketika malam mulai larut, menemani belajar, menyusun makalah yang masih berantakan, atau sekadar memastikan ia tidak merasa sendirian menyelesaikan tugasnya.
                    </p>

                    <p class="animate-fade" style="animation-delay: 800ms;">
                        Rasanya sederhana. Seolah semua itu memang akan terjadi.
                    </p>

                    <p class="animate-fade" style="animation-delay: 1000ms;">
                        Namun waktu selalu punya rencana yang tidak pernah meminta persetujuan. Jalan yang dulu terlihat lurus perlahan berubah menjadi persimpangan, dan pada akhirnya, kita harus melanjutkan langkah masing-masing.
                    </p>

                    <p class="animate-fade" style="animation-delay: 1200ms;">
                        Di suatu titik, aku menyadari bahwa ada janji yang mungkin tidak akan pernah bisa kutepati secara langsung.
                    </p>

                    <p class="animate-fade" style="animation-delay: 1400ms;">
                        Perasaan itu tinggal cukup lama. Bukan tentang penyesalan, melainkan satu pertanyaan yang terus berulang.
                    </p>

                    {{-- Styled blockquote --}}
                    <div class="border-l-2 border-neutral-300 dark:border-neutral-700 pl-5 sm:pl-6 py-2 italic text-neutral-500 dark:text-neutral-400 animate-fade text-lg sm:text-[19px] my-10 sm:my-14 text-left" style="animation-delay: 1600ms;">
                        "Kalau aku tidak bisa menemani, adakah cara lain agar aku tetap bisa membantu?"
                    </div>

                    <p class="animate-fade" style="animation-delay: 1800ms;">
                        Dari pertanyaan kecil itulah, Ruang perlahan dibuat.
                    </p>

                    <p class="animate-fade" style="animation-delay: 2000ms;">
                        Ruang tidak lahir karena dunia membutuhkan aplikasi baru. Ia dibuat agar tetap ada sesuatu yang bisa membantu, bahkan ketika cara untuk hadir sudah tidak lagi sama.
                    </p>

                    <p class="animate-fade" style="animation-delay: 2200ms;">
                        Bukan sesuatu yang rumit. Hanya tempat untuk mengurus hal-hal kecil, agar orang bisa lebih fokus pada gagasan yang benar-benar ingin mereka tulis, tanpa pusing memikirkan margin atau ukuran huruf.
                    </p>

                    <p class="animate-fade" style="animation-delay: 2400ms;">
                        Aku tidak tahu siapa saja yang nantinya akan membuka halaman ini.
                    </p>
                    
                    <p class="animate-fade" style="animation-delay: 2600ms;">
                        Mungkin seseorang yang sedang menyelesaikan tugas pertamanya, yang sedang begadang karena besok adalah tenggat terakhir, atau yang hanya butuh tempat tenang untuk berpikir.
                    </p>
                    
                    <p class="animate-fade" style="animation-delay: 2800ms;">
                        Untuk siapa pun itu, semoga Ruang bisa menemani perjalananmu, walau hanya sebentar.
                    </p>
                    
                    <p class="animate-fade" style="animation-delay: 3000ms;">
                        Dan jika suatu hari tugasmu terasa sedikit lebih ringan karena Ruang, mungkin di situlah sebuah janji lama akhirnya menemukan cara lain untuk ditepati.
                    </p>
                    
                    <p class="animate-fade" style="animation-delay: 3200ms;">
                        Karena pada akhirnya, tidak semua bentuk menemani harus dilakukan dengan berdiri di sisi seseorang. Kadang, menemani berarti meninggalkan sesuatu yang akan tetap ada untuk membantunya.
                    </p>

                    {{-- Closing poem --}}
                    <div class="mt-20 sm:mt-32 pt-8 text-center space-y-2 text-neutral-500 dark:text-neutral-400 italic font-instrument text-lg sm:text-2xl tracking-wide animate-fade" style="animation-delay: 3400ms;">
                        <p>Barangkali,</p>
                        <p>beberapa janji memang</p>
                        <p>tidak pernah benar-benar hilang.</p>
                        <p class="pt-6 sm:pt-8">Mereka hanya berubah bentuk.</p>
                    </div>

                    {{-- Small date / text at the very bottom --}}
                    <div class="pt-24 sm:pt-32 text-center text-[9px] sm:text-[10px] text-neutral-400 dark:text-neutral-500 uppercase tracking-widest animate-fade" style="animation-delay: 3600ms;">
                        ditulis pada musim ketika sebuah janji akhirnya menemukan bentuk lain.
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
