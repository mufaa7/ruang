@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-300 dark:border-slate-800 dark:bg-slate-900/50 dark:text-slate-200 focus:border-indigo-500 dark:focus:border-indigo-500 focus:ring-indigo-500 dark:focus:ring-indigo-500 rounded-xl shadow-sm min-h-11 px-4 text-sm transition-all duration-200']) }}>
