<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MakalahChapter extends Model
{
    protected $fillable = [
        'makalah_id', 'title', 'content', 'order',
        'type', 'bab_number', 'sub_sections',
    ];

    protected $casts = [
        'sub_sections' => 'array',
    ];

    // ── Accessors ─────────────────────────────────────────────────────────────

    /**
     * Label romawi untuk bab: BAB I, BAB II, dst
     */
    public function getBabLabelAttribute(): string
    {
        if ($this->type !== 'bab' || ! $this->bab_number) {
            return '';
        }
        return 'BAB ' . $this->toRoman($this->bab_number);
    }

    /**
     * Word count untuk estimasi panjang
     */
    public function getWordCountAttribute(): int
    {
        return str_word_count(strip_tags($this->content ?? ''));
    }

    public function getParsedContentAttribute(): string
    {
        $content = $this->content ?? '';
        // Ubah ((isi)) jadi span atau mark untuk PDF/Web view
        return preg_replace('/\(\((.*?)\)\)/', '<sup class="text-xs text-stone-500 bg-stone-100 px-1 rounded" style="font-size:10px;vertical-align:super;">[$1]</sup>', $content);
    }

    // ── Relations ─────────────────────────────────────────────────────────────
    public function makalah(): BelongsTo
    {
        return $this->belongsTo(Makalah::class);
    }

    public function subchapters()
    {
        return $this->hasMany(MakalahSubchapter::class)->orderBy('order');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function toRoman(int $n): string
    {
        $map = [
            1000 => 'M', 900 => 'CM', 500 => 'D', 400 => 'CD',
            100  => 'C', 90  => 'XC', 50  => 'L', 40  => 'XL',
            10   => 'X', 9   => 'IX', 5   => 'V', 4   => 'IV', 1 => 'I',
        ];
        $result = '';
        foreach ($map as $value => $numeral) {
            while ($n >= $value) {
                $result .= $numeral;
                $n -= $value;
            }
        }
        return $result;
    }
}
