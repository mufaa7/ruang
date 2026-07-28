<section id="ending">
    <div class="ending-container">
        <h2>
            sampai jumpa<br>
            di dalam.
        </h2>
        <a
            href="{{ auth()->check() ? route('dashboard') : route('login') }}"
            class="ending-button">
            masuk &rarr;
        </a>
    </div>
</section>
