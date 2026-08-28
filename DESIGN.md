---
name: RUANG Design System
description: Cozy dark glassmorphism & vintage indie craft workspace
colors:
  bg-deep: "#0b101e"
  card-glass: "rgba(255, 255, 255, 0.05)"
  card-border: "rgba(255, 255, 255, 0.10)"
  accent-amber: "#fcd34d"
  accent-amber-glow: "rgba(252, 211, 77, 0.2)"
  text-primary: "#ffffff"
  text-secondary: "#cbd5e1"
  text-muted: "#94a3b8"
  status-success: "#34d399"
  status-danger: "#f87171"
  status-info: "#60a5fa"
typography:
  display:
    fontFamily: "Cormorant Garamond, Georgia, serif"
    fontWeight: 400
  headings:
    fontFamily: "Geist, Inter, sans-serif"
    fontWeight: 700
  body:
    fontFamily: "Inter, sans-serif"
    fontWeight: 400
    lineHeight: 1.6
  editor:
    fontFamily: "Newsreader, Merriweather, Georgia, serif"
    lineHeight: 1.8
rounded:
  sm: "8px"
  md: "12px"
  lg: "16px"
  xl: "24px"
  full: "9999px"
spacing:
  xs: "4px"
  sm: "8px"
  md: "16px"
  lg: "24px"
  xl: "32px"
---

## Overview
RUANG mengusung estetika **Quiet Minimalism** — suasana ruang belajar malam yang tenang, matang, dan bebas dari distraksi visual. Menghindari dekorasi artifisial, efek neon/glow berlebih, dan gradien mencolok. Fokus utama adalah tipografi yang tajam, kontras tinggi, hairline border presisi, dan permukaan kaca matte gelap yang nyaman di mata.

## Colors
- **Canvas Base**: `#090d16` (Deep Midnight Matte) — latar belakang gelap bersih dan solid.
- **Glass Surfaces**: `bg-white/[0.03]` hingga `bg-black/30` dengan `backdrop-blur-md` dan hairline border `border-white/10`.
- **Primary Text**: `#f8fafc` (Slate 50) untuk keterbacaan tajam.
- **Secondary & Muted Text**: `#cbd5e1` (Slate 300) dan `#94a3b8` (Slate 400).
- **Subdued Accent**: `amber-300` (`#fcd34d`) digunakan secara hemat dan fungsional hanya sebagai indikator fokus/status aktif, tanpa efek halo/neon glow berlebih.

## Typography
- **Headings & UI**: `Inter` / `Geist` (`font-bold`, `tracking-tight`).
- **Quote & Editorial**: `Cormorant Garamond` untuk sentuhan literatur klasik yang tenang.
- **Technical & Time**: `font-mono` untuk timer, durasi, dan counter.

## Do's and Don'ts
- **DO**: Pertahankan hairline border `border-white/10` yang tipis dan elegan.
- **DO**: Gunakan ruang kosong (*whitespace*) yang lapang untuk memberi kesan tenang.
- **DON'T**: Jangan gunakan efek neon glow/halo (`shadow-[0_0_15px...]`) atau gradien warna-warni yang ramai (*norak*).
- **DON'T**: Jangan gunakan teks abu-abu rendah kontras di atas tombol atau latar warna.
- **DON'T**: Jangan gunakan animasi berlebihan (*bouncing*, *pulsing halos*). Gunakan transisi mikro yang halus (`150ms ease`).
