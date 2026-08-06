# RUANG - Sidebar & Navigation Specification (MVP)

## Filosofi Produk

RUANG bukan aplikasi kampus.

RUANG bukan Notion.

RUANG bukan Google Docs.

RUANG adalah ruang kerja mahasiswa.

Tujuan utama aplikasi adalah membuat mahasiswa bisa membuka aplikasi dan langsung mengerjakan tugas tanpa harus memikirkan format, folder, ataupun aplikasi lain.

Semua fitur harus mengikuti prinsip:

Masuk → pake headset → nugas → Pulang (taruh di bawah RUANG)

Sidebar harus sederhana, tidak lebih dari 8 menu utama.

---

# Sidebar

🏠 Beranda

✍️ Nugas

📚 Belajar

💭 Coretan

🎯 Latihan

🎧 Dengerin

📈 Jejak

⚙️ Ruangku

---

# 🏠 Beranda

## Tujuan

Halaman utama aplikasi.

Berfungsi sebagai pusat aktivitas pengguna.

Pengguna harus dapat melanjutkan pekerjaan hanya dengan satu klik.

---
## quotes
## Widget

### Continue Last Work

Menampilkan pekerjaan terakhir.

Contoh:

- Makalah
- Catatan
- Coretan

Terdapat tombol

[Lanjutkan]

---

### Quick Action

- Makalah Baru
- Catatan Baru
- Coretan Baru

---

### Aktivitas Terbaru

Menampilkan:

- Makalah terakhir
- Catatan terakhir
- Quiz terakhir

---

### Deadline

Menampilkan tugas yang mendekati deadline.

---

### Spotify

Widget kecil

Now Playing

---

### Progress Hari Ini

- Kata ditulis
- Menit fokus
- Catatan dibuat
- Quiz selesai

---

# ✍️ Nugas (FITUR UTAMA)

## Tujuan

Editor utama aplikasi.

RUANG harus mampu menggantikan Microsoft Word untuk kebutuhan makalah mahasiswa.

Semua proses dilakukan di dalam editor.

---

## Halaman

- Semua Makalah
- Draft
- Selesai
- Template Kampus
- Makalah Baru

---

## Editor

Harus memiliki fitur:

- Auto Save
- Version History
- Rich Text
- Markdown
- Gambar
- Tabel
- Rumus
- Checklist
- Heading
- Cover Otomatis
- Halaman Identitas
- Kata Pengantar
- Daftar Isi Otomatis
- BAB I
- BAB II
- BAB III
- BAB IV
- BAB V
- Daftar Pustaka
- Lampiran
- Margin Otomatis
- Nomor Halaman
- Header
- Footer
- Export DOCX
- Export PDF

---

## Sidebar Editor

Cover

Identitas

Kata Pengantar

Daftar Isi

BAB I

BAB II

BAB III

BAB IV

BAB V

Daftar Pustaka

Lampiran

---

## User Flow

Beranda

↓

Makalah Baru

↓

Isi Informasi Makalah

↓

Editor

↓

Export DOCX

↓

Selesai

---

# 📚 Belajar

## Tujuan

Tempat menyimpan seluruh catatan mata kuliah.

Satu mata kuliah memiliki satu workspace.

---

## Struktur

Mata Kuliah

↓

Catatan

↓

Materi

↓

Lampiran

---

## Catatan

Mendukung

- Rich Text
- Markdown
- Gambar
- Rumus
- Checklist
- Highlight

---

## Materi

Upload

- PDF
- PPT
- DOCX
- Image

---

## Search

Cari seluruh isi catatan.

---

## Folder

Pisahkan catatan berdasarkan mata kuliah.

---

## User Flow

Belajar

↓

Pilih Mata Kuliah

↓

Catatan

↓

Tambah Catatan

↓

Simpan

---

# 💭 Coretan

## Tujuan

Catatan bebas.

Tidak berhubungan dengan mata kuliah.

Tidak memiliki struktur akademik.

---

## Contoh

- Diary
- Quotes
- To Do
- Checklist
- Wishlist
- Draft Caption
- Ide Startup
- Curhat
- Meeting Notes

---

## Fitur

- Folder
- Favorite
- Tag
- Reminder
- Search
- Auto Save
- Rich Text

---

## User Flow

Coretan

↓

Catatan Baru

↓

Tulis

↓

Simpan

---

# 🎯 Latihan

## Tujuan

Tempat menguji pemahaman materi.

Quiz dibuat dari catatan atau materi.

---

## Sumber

- Catatan
- Materi
- PDF
- PPT
- DOCX

---

## Jenis Soal

- Pilihan Ganda
- Essay
- Benar / Salah
- Isian Singkat

---

## Hasil

Menampilkan:

- Nilai
- Pembahasan
- Jawaban Benar
- Jawaban Salah

---

## User Flow

Belajar

↓

Pilih Mata Kuliah

↓

Generate Quiz

↓

Kerjakan

↓

Lihat Nilai

---

# 🎧 Dengerin

## Tujuan

Mendengarkan musik ketika belajar.

Terhubung dengan Spotify.

---

## Fitur

- Connect Spotify
- Now Playing
- Playlist
- Recently Played
- Study Playlist
- Focus Playlist

---

## Focus Mode

- Pomodoro
- Timer
- Statistik Fokus

---

## User Flow

Dengerin

↓

Pilih Playlist

↓

Play

↓

Belajar

---

# 📈 Jejak

## Tujuan

Melihat perkembangan belajar.

---

## Statistik

- Total Makalah
- Total Catatan
- Total Coretan
- Total Quiz
- Total Kata Ditulis
- Hari Aktif
- Jam Fokus
- Streak

---

## Insight

Contoh

"Minggu ini kamu menulis 14.520 kata."

"Makalah yang selesai bulan ini: 6."

---

# ⚙️ Ruangku

## Tujuan

Pengaturan aplikasi dan profil pengguna.

---

## Profil

- Nama
- Foto
- Email
- Password

---

## Editor

- Tema
- Font
- Ukuran Font
- Auto Save
- Template Default

---

## Spotify

- Hubungkan Akun
- Putuskan Akun

---

## AI (Persiapan V2)

Menu ini belum aktif pada MVP.

Hanya menyediakan:

- Gemini API Key
- Pilihan Model

---

# Arsitektur AI (V2)

RUANG tidak boleh menggunakan Gemini untuk semua fitur.

Harus menggunakan AI Router.

Flow:

User Request

↓

Router

↓

Apakah bisa dikerjakan Python?

YES

↓

Python Engine

NO

↓

Gemini

---

## Python Engine

Digunakan untuk:

- Search
- Word Count
- Reading Time
- Highlight Keyword
- DOCX Parser
- PDF Parser
- Statistik
- OCR
- Flashcard sederhana
- Quiz sederhana
- Analytics

---

## Gemini

Digunakan hanya untuk:

- Humanize
- Rewrite
- Explain
- Brainstorm
- Citation
- Footnote
- Penjelasan Materi
- Analisis Jawaban Essay

---

# Prinsip UI

- Minimalis
- Fokus pada editor
- Maksimal 3 klik untuk mencapai fitur apa pun
- Tidak ada popup yang mengganggu
- Auto Save di seluruh editor
- Sidebar selalu terlihat
- Responsive
- Dark Mode sebagai tema utama
- Semua editor menggunakan shortcut keyboard

---

# Identitas RUANG

RUANG bukan aplikasi AI.

AI hanyalah alat bantu.

Fitur utama tetap:

✍️ Nugas

Jika AI gagal, aplikasi tetap dapat digunakan sepenuhnya.

Mahasiswa tetap bisa membuat makalah, mencatat materi, belajar, mendengarkan musik, dan melihat progres tanpa bergantung pada AI.