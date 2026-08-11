<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-6 min-h-[44px] bg-rose-500/10 border border-rose-500/30 rounded-xl font-bold text-sm text-rose-400 hover:bg-rose-500/20 active:scale-95 transition-all shadow-sm flex gap-2']) }}>
    {{ $slot }}
</button>
