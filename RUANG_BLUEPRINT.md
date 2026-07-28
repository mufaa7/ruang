
# RUANG_PRODUCT_BLUEPRINT.md

# RUANG
**Tagline**
> Masuk ke ruangmu. Belajar. Nulis. Denger lagu. Pulang.

---

# PRODUCT VISION

RUANG adalah editor akademik untuk mahasiswa Indonesia.

Fokus utama BUKAN AI.
Fokus utama BUKAN Notes.

Fokus utama adalah:

> Membuat proses mengerjakan makalah menjadi semudah mungkin.

Semua fitur lain (Notes, Journal, AI, Spotify, Analytics) hanya mendukung tujuan tersebut.

---

# NORTH STAR

Open App
→ New Paper
→ Write
→ Export DOCX
→ Submit

Jika sebuah fitur tidak membantu alur tersebut, jangan menjadi prioritas MVP.

---

# MVP

1. Editor Makalah
2. Auto Formatting
3. AI Assistant
4. Export DOCX/PDF

---

# HOME

Tampilkan hanya:

- Makalah Baru
- Catatan Baru
- Buka Workspace
- Lanjutkan Pekerjaan Terakhir

Tidak perlu dashboard penuh pada MVP.

---

# NEW PAPER FLOW

Klik Makalah Baru

↓

Isi:
- Judul
- Mata Kuliah
- Dosen
- Universitas/Template
- Nama
- NIM
- Kelas
- Program Studi
- Tahun Akademik

↓

Generate Workspace Makalah

---

# PAPER SIDEBAR

Cover

Identitas

Kata Pengantar (opsional)

Daftar Isi

BAB I

BAB II

BAB III

BAB IV

BAB V

Daftar Pustaka

Lampiran

Sidebar harus drag & drop.

---

# EDITOR

Rich Text

Auto Save

Heading

Checklist

Image

Table

Equation

Code Block

Highlight

Comments

Version History

Auto Page Break

Auto Numbering

---

# RIGHT PANEL

Duck Mode

Rewrite

Humanize

Citation

Footnote

Now Playing

Pomodoro

Word Count

---

# AUTO FORMAT

Times New Roman 12

Line Spacing 1.5

Margin:
Left 4
Right 3
Top 3
Bottom 3

Justify

Indent

Page Number

Heading Style

Table Style

Caption

Automatic TOC

---

# PYTHON ENGINE

Python FIRST.

Modules:

Summary

Keyword Extraction

Auto Tag

Flashcard

Quiz

Search

Mood Analysis

DOCX Parser

PDF Parser

Charts

Grammar

Statistics

Python Service:

FastAPI

REST API

Response JSON only.

---

# GEMINI

Use only for:

Duck Mode

Rewrite

Humanize

Brainstorm

Academic Expansion

Citation Recommendation

Never call Gemini if Python is enough.

---

# AI ROUTER

User Request

↓

Classifier

↓

Python capable?

YES → Python

NO → Gemini

Log request type.

---

# NOTES

Notes are secondary.

Structure

Workspace

└── Course

    ├── Notes

    ├── Files

    ├── Flashcards

    ├── Quiz

Notes support:
- Markdown
- Rich Text
- Auto Tag
- Search

---

# JOURNAL

Independent feature.

Mood

Reflection

Weekly insight

Private.

---

# MUSIC

Spotify Integration.

Now Playing

Playlist

Focus Playlist

No music storage locally.

---

# DATABASE

users

semesters

courses

papers

paper_sections

paper_templates

paper_exports

notes

materials

tasks

references

flashcards

quizzes

journals

focus_sessions

music_sessions

analytics_daily

settings

---

# LARAVEL

Use:

Form Request

Service Layer

Repository when complexity grows

Policies

Observers when needed

Soft Delete

Factories

Seeders

Never put business logic inside controllers.

---

# PYTHON FOLDER

python-engine/

api/

services/

models/

utils/

tests/

nlp/

---

# API

POST /summary

POST /keywords

POST /flashcards

POST /quiz

POST /mood

POST /charts

POST /grammar

POST /router

---

# ROADMAP

V1
Editor
Formatting
DOCX
PDF

V2
Duck Mode
Citation
Footnote
Humanize
Python Engine

V3
Notes
Workspace
Flashcards
Quiz

V4
Spotify
Journal
Analytics

---

# DEFINITION OF DONE

Every module MUST include:

Migration

Model

Relationship

Validation

Controller

Service

Routes

Views

Factory

Seeder

Testing steps

No placeholder

Production Ready

---

# MASTER PROMPT FOR DEEPSEEK

You are the Lead Software Engineer of RUANG.

Read ALL markdown files inside docs/ and .ai/ before making any changes.

Primary objective:
Build the best academic paper editor for Indonesian students.

Priority:
1. Paper Editor
2. Automatic Formatting
3. Export DOCX/PDF
4. Python Intelligence
5. AI Features
6. Notes Workspace
7. Journal
8. Spotify

Rules:
- Laravel 11
- PHP 8.3
- Tailwind CSS
- Blade + Livewire
- MySQL
- FastAPI for Python
- Clean Architecture
- SOLID
- RESTful API

Never generate tutorial code.
Never generate placeholder.
Never generate pseudo code.
Never modify unrelated files.

Before coding:
- Explain architecture
- Explain database changes
- Explain affected modules

After coding:
- List created files
- List modified files
- Explain testing
- Wait for the next task.

Think carefully before writing code.
