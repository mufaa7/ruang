<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-4 min-h-11 bg-rose-600 border border-transparent rounded-xl font-medium text-sm text-white hover:bg-rose-500 active:bg-rose-700 active:scale-95 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 transition-all duration-200']) }}>
    {{ $slot }}
</button>
