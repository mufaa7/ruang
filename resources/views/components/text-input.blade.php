@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-200 focus:border-neutral-800 dark:focus:border-neutral-800 focus:ring-stone-800 dark:focus:ring-stone-800 rounded-xl shadow-sm min-h-11 px-4 text-sm transition-all duration-200']) }}>
