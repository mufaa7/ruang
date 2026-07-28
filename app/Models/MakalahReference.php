<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakalahReference extends Model
{
    protected $fillable = [
        'makalah_id', 'type', 'penulis', 'judul', 'tahun',
        'penerbit', 'kota_terbit', 'nama_jurnal', 'volume',
        'nomor', 'halaman', 'url', 'tanggal_akses', 'doi', 'order', 'raw_citation',
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Format sitasi APA otomatis berdasarkan tipe
     */
    public function getApaCitationAttribute(): string
    {
        return match ($this->type) {
            'buku'    => $this->formatBuku(),
            'jurnal'  => $this->formatJurnal(),
            'web'     => $this->formatWeb(),
            'skripsi' => $this->formatSkripsi(),
            default   => $this->formatLainnya(),
        };
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function makalah(): BelongsTo
    {
        return $this->belongsTo(Makalah::class);
    }

    // ── Private formatters ────────────────────────────────────────────────────
    private function formatBuku(): string
    {
        $parts = [];
        $parts[] = $this->penulis . ($this->tahun ? ' (' . $this->tahun . ').' : '.');
        $parts[] = '<em>' . $this->judul . '</em>.';
        if ($this->kota_terbit && $this->penerbit) {
            $parts[] = $this->kota_terbit . ': ' . $this->penerbit . '.';
        } elseif ($this->penerbit) {
            $parts[] = $this->penerbit . '.';
        }
        return implode(' ', $parts);
    }

    private function formatJurnal(): string
    {
        $parts = [];
        $parts[] = $this->penulis . ($this->tahun ? ' (' . $this->tahun . ').' : '.');
        $parts[] = $this->judul . '.';
        if ($this->nama_jurnal) {
            $j = '<em>' . $this->nama_jurnal . '</em>';
            if ($this->volume) $j .= ', <em>' . $this->volume . '</em>';
            if ($this->nomor)  $j .= '(' . $this->nomor . ')';
            if ($this->halaman) $j .= ', ' . $this->halaman;
            $parts[] = $j . '.';
        }
        if ($this->doi) $parts[] = 'https://doi.org/' . $this->doi;
        return implode(' ', $parts);
    }

    private function formatWeb(): string
    {
        $parts = [];
        $parts[] = $this->penulis . ($this->tahun ? ' (' . $this->tahun . ').' : '.');
        $parts[] = '<em>' . $this->judul . '</em>.';
        if ($this->url) {
            $parts[] = 'Diakses dari ' . $this->url;
            if ($this->tanggal_akses) $parts[] = 'pada ' . $this->tanggal_akses;
        }
        return implode(' ', $parts);
    }

    private function formatSkripsi(): string
    {
        $parts = [];
        $parts[] = $this->penulis . ($this->tahun ? ' (' . $this->tahun . ').' : '.');
        $parts[] = '<em>' . $this->judul . '</em>.';
        if ($this->penerbit) $parts[] = '[Skripsi tidak diterbitkan]. ' . $this->penerbit . '.';
        return implode(' ', $parts);
    }

    private function formatLainnya(): string
    {
        return $this->penulis . ($this->tahun ? ' (' . $this->tahun . '). ' : '. ')
            . $this->judul . '.';
    }
}
