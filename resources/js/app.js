
import Alpine from 'alpinejs';

window.Alpine = Alpine;

document.addEventListener('alpine:init', () => {
    Alpine.store('pomodoro', {
        mode: 'idle', // idle, focus, break
        timeLeft: 25 * 60,
        isRunning: false,
        timer: null,

        get formattedTime() {
            let m = Math.floor(this.timeLeft / 60);
            let s = this.timeLeft % 60;
            return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
        },

        startFocus() {
            this.mode = 'focus';
            this.timeLeft = 25 * 60;
            this.play();
        },

        startBreak() {
            this.mode = 'break';
            this.timeLeft = 5 * 60;
            this.play();
        },
        
        setMode(minutes) {
            this.mode = minutes === 25 ? 'focus' : 'break';
            this.pause();
            this.timeLeft = minutes * 60;
        },

        play() {
            if (this.mode === 'idle') this.mode = 'focus';
            this.isRunning = true;
            this.timer = setInterval(() => {
                if (this.timeLeft > 0) {
                    this.timeLeft--;
                } else {
                    this.complete();
                }
            }, 1000);
        },

        pause() {
            this.isRunning = false;
            clearInterval(this.timer);
        },

        toggle() {
            if (this.isRunning) this.pause();
            else this.play();
        },

        reset() {
            this.pause();
            if (this.mode === 'break') {
                this.timeLeft = 5 * 60;
            } else {
                this.mode = 'idle';
                this.timeLeft = 25 * 60;
            }
        },
        
        skipSession() {
            if (this.mode === 'focus' || this.mode === 'idle') {
                this.mode = 'break';
                this.timeLeft = 5 * 60;
            } else {
                this.mode = 'focus';
                this.timeLeft = 25 * 60;
            }
            this.pause();
        },

        complete() {
            this.pause();
            this.playSound();
            
            if (this.mode === 'focus') {
                window.dispatchEvent(new Event('pomodoro-finished'));
                
                if (confirm('udah 25 menit. minum dulu bentar.')) {
                    this.startBreak();
                } else {
                    this.reset();
                }
            } else {
                alert('Waktu istirahat selesai! Kembali fokus yuk!');
                this.mode = 'idle';
                this.reset();
            }
        },

        playSound() {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.type = 'sine';
            osc.frequency.value = 800;
            gain.gain.setValueAtTime(0, ctx.currentTime);
            gain.gain.linearRampToValueAtTime(1, ctx.currentTime + 0.05);
            gain.gain.linearRampToValueAtTime(0, ctx.currentTime + 0.5);
            osc.start(ctx.currentTime);
            osc.stop(ctx.currentTime + 0.5);
        }
    });
    Alpine.store('sidebar', {
        isOpen: window.innerWidth >= 1024,
        toggle() {
            this.isOpen = !this.isOpen;
            if (window.innerWidth < 1024) {
                document.body.style.overflow = this.isOpen ? 'hidden' : '';
            }
        }
    });

    Alpine.store('musicPlayer', {
        isMaximized: false,
        isMinimized: true,
        iframesLoaded: false,
        miniPlayerVisible: false, // Mini player hanya muncul setelah user mulai dengerin

        // Dipanggil dari tombol "Mulai Dengerin" di halaman /dengerin
        startListening() {
            this.iframesLoaded = true;
            // miniPlayerVisible akan di-set oleh Spotify postMessage saat user play lagu
        },

        // Tutup mini player (tidak menutup /dengerin)
        closeMiniPlayer() {
            this.miniPlayerVisible = false;
            this.isMinimized = true;
        },

        toggleMinimize() {
            // Lazy mount: iframe dibuat pertama kali user expand mini player
            if (this.isMinimized && !this.iframesLoaded) {
                this.iframesLoaded = true;
            }
            this.isMinimized = !this.isMinimized;
        }
    });
});

const initMusicPlayer = () => {
    if (window.Alpine && Alpine.store('musicPlayer')) {
        const store = Alpine.store('musicPlayer');
        store.isMaximized = window.location.pathname === '/dengerin';

        if (store.isMaximized) {
            store.iframesLoaded = true;
        }
    }
};

const syncActiveSidebarLinks = () => {
    const currentPath = window.location.pathname;
    document.querySelectorAll('#app-sidebar nav a').forEach(link => {
        const href = link.getAttribute('href');
        if (href) {
            try {
                const url = new URL(href, window.location.origin);
                const isMatch = (url.pathname === currentPath) || (url.pathname !== '/' && url.pathname !== '/dashboard' && currentPath.startsWith(url.pathname));
                if (isMatch) {
                    link.classList.add('bg-white/10', 'text-amber-300', 'border-amber-300', 'shadow-[inset_2px_0_10px_rgba(252,211,77,0.05)]');
                    link.classList.remove('text-slate-300', 'border-transparent');
                } else {
                    link.classList.remove('bg-white/10', 'text-amber-300', 'border-amber-300', 'shadow-[inset_2px_0_10px_rgba(252,211,77,0.05)]');
                    link.classList.add('text-slate-300', 'border-transparent');
                }
            } catch (e) {}
        }
    });
};

// Mobile UX: Tutup sidebar tepat saat halaman tujuan siap dirender (tidak prematur di before-visit)
document.addEventListener('turbo:before-cache', () => {
    if (window.innerWidth < 1024 && window.Alpine && Alpine.store('sidebar')) {
        Alpine.store('sidebar').isOpen = false;
        document.body.style.overflow = '';
    }
});

document.addEventListener('turbo:before-render', () => {
    if (window.innerWidth < 1024 && window.Alpine && Alpine.store('sidebar')) {
        Alpine.store('sidebar').isOpen = false;
        document.body.style.overflow = '';
    }
});

document.addEventListener('turbo:load', () => {
    initMusicPlayer();
    syncActiveSidebarLinks();
    if (window.innerWidth < 1024 && window.Alpine && Alpine.store('sidebar')) {
        Alpine.store('sidebar').isOpen = false;
        document.body.style.overflow = '';
    }
});

document.addEventListener('turbo:frame-load', () => {
    initMusicPlayer();
    syncActiveSidebarLinks();
});


// Deteksi play event dari Spotify iframe via postMessage (cross-origin)
// Spotify embed mengirim 'playback_update' dengan isPaused=false saat lagu mulai diputar
window.addEventListener('message', (event) => {
    if (!event.origin || !event.origin.includes('spotify.com')) return;
    try {
        const data = typeof event.data === 'string' ? JSON.parse(event.data) : event.data;
        if (data?.type === 'playback_update' && data?.payload?.isPaused === false) {
            if (window.Alpine && Alpine.store('musicPlayer')) {
                Alpine.store('musicPlayer').miniPlayerVisible = true;
            }
        }
    } catch (e) {
        // Abaikan pesan non-JSON atau dari origin lain
    }
});

Alpine.start();
