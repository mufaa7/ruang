<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Makalah extends Model
{
    use SoftDeletes;

    public const DEFAULT_BAB = [
        [
            'nomor' => 1,
            'judul' => 'Pendahuluan',
            'sub' => [
                'Latar Belakang',
                'Rumusan Masalah',
                'Tujuan Penelitian'
            ]
        ],
        [
            'nomor' => 2,
            'judul' => 'Kajian Teori',
            'sub' => [
                'Landasan Teori',
                'Penelitian Terdahulu',
                'Kerangka Pemikiran'
            ]
        ],
        [
            'nomor' => 3,
            'judul' => 'Metode Penelitian',
            'sub' => [
                'Jenis Penelitian',
                'Sumber Data',
                'Teknik Analisis'
            ]
        ],
        [
            'nomor' => 4,
            'judul' => 'Hasil dan Pembahasan',
            'sub' => [
                'Hasil Penelitian',
                'Pembahasan'
            ]
        ],
        [
            'nomor' => 5,
            'judul' => 'Penutup',
            'sub' => [
                'Simpulan',
                'Saran'
            ]
        ]
    ];

    protected $table = 'makalah';

    protected $fillable = [
        'user_id', 'judul', 'sub_judul', 'nama_penulis', 'nim',
        'nama_dosen', 'mata_kuliah', 'universitas', 'fakultas',
        'prodi', 'kota', 'tahun', 'logo_path', 'logo_url',
        'settings', 'status', 'jenis_dokumen', 'kata_pengantar',
        'ai_status', 'ai_progress',
    ];

    protected $casts = [
        'settings' => 'array',
    ];

    // ── Default settings ──────────────────────────────────────────────────────
    public function getDefaultSettings(): array
    {
        return [
            'font_size'         => 12,
            'font_family'       => 'Times New Roman',
            'line_height'       => 1.5,
            'margin_top'        => 3,
            'margin_right'      => 3,
            'margin_bottom'     => 3,
            'margin_left'       => 3,
            'page_number_style' => 'mixed',   // mixed | arabic | none
            'citation_style'    => 'apa',
        ];
    }

    public function getSetting(string $key): mixed
    {
        $settings = array_merge($this->getDefaultSettings(), $this->settings ?? []);
        return $settings[$key] ?? null;
    }

    // ── Accessors ─────────────────────────────────────────────────────────────
    public function getBabsAttribute()
    {
        return $this->chapters->where('type', 'bab')->sortBy('order');
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(MakalahChapter::class)->orderBy('order');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(MakalahContent::class);
    }

    public function getContent($bab, $sub = null)
    {
        return $this->contents()
            ->where('bab', $bab)
            ->where('sub', $sub)
            ->value('content');
    }

    public function references(): HasMany
    {
        return $this->hasMany(MakalahReference::class)->orderBy('order');
    }

    // Helper: berapa bab yang ada
    public function nextBabNumber(): int
    {
        return ($this->chapters()->where('type', 'bab')->max('bab_number') ?? 0) + 1;
    }
}
