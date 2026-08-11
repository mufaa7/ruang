<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex justify-center items-center px-6 min-h-[44px] bg-transparent border border-white/10 rounded-xl font-bold text-sm text-slate-300 shadow-sm hover:bg-white/5 hover:text-white focus:outline-none disabled:opacity-25 active:scale-95 transition-all flex gap-2']) }}>
    {{ $slot }}
</button>
