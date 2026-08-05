<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex justify-center items-center px-4 min-h-11 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-xl font-medium text-sm text-slate-700 dark:text-slate-300 shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:ring-offset-2 dark:focus:ring-offset-slate-800 disabled:opacity-25 active:scale-95 transition-all duration-200']) }}>
    {{ $slot }}
</button>
