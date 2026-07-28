<section id="hero">
    <div class="hero-container">
        <div class="hero-left">
            <span class="hero-label">
                for writing,<br>
                learning,<br>
                and wonderwall.
            </span>
            <div class="hero-divider"></div>
            <h1 class="hero-title">
                masuk ke <br>
                ruangmu.
            </h1>
            <div class="hero-ritual">
                <span>belajar.</span>
                <span>menulis.</span>
                <span class="playing">
                    ▶ denger lagu.
                </span>
                <span>pulang.</span>
            </div>
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}"
                class="hero-button">
                masuk &rarr;
            </a>
        </div>
        <div class="hero-right">
            <blockquote>
                Jika waktu benar-benar mengalir,
                mengapa sebagian kenangan
                justru mengendap?
            </blockquote>
            <div class="hero-song">
                <small>♫ always playing</small>
                <h3>Wonderwall</h3>
                <p>Oasis</p>
            </div>
        </div>
    </div>
</section>
