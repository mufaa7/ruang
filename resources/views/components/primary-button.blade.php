<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-6 min-h-[44px] bg-white/10 border border-white/10 rounded-xl font-bold text-sm text-white hover:bg-white/20 active:scale-95 transition-all shadow-sm flex gap-2']) }}>
    {{ $slot }}
</button>
