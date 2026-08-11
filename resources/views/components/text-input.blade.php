@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full rounded-xl border border-white/10 bg-white/5 px-4 min-h-[44px] text-sm text-white focus:border-amber-500/50 focus:ring-1 focus:ring-amber-500/50 transition-all shadow-inner placeholder:text-slate-500']) }}>
