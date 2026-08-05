<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-4 min-h-11 bg-slate-900 dark:bg-neutral-900 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-slate-800 dark:hover:bg-stone-700 focus:bg-slate-800 dark:focus:bg-neutral-900 active:scale-95 focus:outline-none focus:ring-2 focus:ring-stone-800 focus:ring-offset-2 dark:focus:ring-offset-slate-900 transition-all duration-200']) }}>
    {{ $slot }}
</button>
