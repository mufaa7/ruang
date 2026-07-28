<?php

namespace App\Services;

use App\Models\Makalah;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\Shared\Converter;

class MakalahExportService
{
    // ── PDF Export ────────────────────────────────────────────────────────────

    public function exportPdf(Makalah $makalah): \Barryvdh\DomPDF\PDF
    {
        // Increase execution time for DOMPDF (can be slow with large content/images)
        set_time_limit(300);

        $makalah->load(['chapters', 'references']);

        $marginTop    = $makalah->getSetting('margin_top');    // cm
        $marginRight  = $makalah->getSetting('margin_right');
        $marginBottom = $makalah->getSetting('margin_bottom');
        $marginLeft   = $makalah->getSetting('margin_left');

        // cm → mm for dompdf
        $marginTopMm    = $marginTop    * 10;
        $marginRightMm  = $marginRight  * 10;
        $marginBottomMm = $marginBottom * 10;
        $marginLeftMm   = $marginLeft   * 10;

        $pdf = Pdf::loadView('makalah.pdf', [
            'makalah'         => $makalah,
            'margin_top'      => $marginTopMm,
            'margin_right'    => $marginRightMm,
            'margin_bottom'   => $marginBottomMm,
            'margin_left'     => $marginLeftMm,
            'font_size'       => $makalah->getSetting('font_size'),
            'line_height'     => $makalah->getSetting('line_height'),
        ]);

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled'      => true,
            'defaultFont'          => 'serif',
            'dpi'                  => 150,
        ]);

        return $pdf;
    }

    // ── Word Export ───────────────────────────────────────────────────────────

    public function exportWord(Makalah $makalah): string
    {
        // Prevent XML corruption by escaping special characters like '&', '<', '>'
        \PhpOffice\PhpWord\Settings::setOutputEscapingEnabled(true);

        $makalah->load(['chapters', 'references']);

        $phpWord = new PhpWord();

        // ── Pengaturan dokumen ─────────────────────────────────────────────
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize($makalah->getSetting('font_size'));
        $phpWord->getSettings()->setThemeFontLang(new \PhpOffice\PhpWord\Style\Language('id-ID'));
        // Auto-update TOC and all fields when Word opens the file
        $phpWord->getSettings()->setUpdateFields(true);

        // Margin: cm → twip (1 cm = 566.9 twip)
        $twip = fn(float $cm) => (int) round($cm * 566.9);
        $pageSettings = [
            'marginTop'    => $twip($makalah->getSetting('margin_top')),
            'marginRight'  => $twip($makalah->getSetting('margin_right')),
            'marginBottom' => $twip($makalah->getSetting('margin_bottom')),
            'marginLeft'   => $twip($makalah->getSetting('margin_left')),
        ];

        // ── Styles ────────────────────────────────────────────────────────
        $phpWord->addTitleStyle(1, ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::CENTER]);
        $phpWord->addTitleStyle(2, ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'], ['alignment' => Jc::START]);
        $phpWord->addParagraphStyle('Normal', [
            'lineHeight'  => $makalah->getSetting('line_height'),
            'alignment'   => Jc::BOTH,
            'spaceBefore' => 0,
            'spaceAfter'  => 0,
        ]);
        
        // Prevent TOC from inheriting the firstLine indentation from Normal
        $phpWord->addParagraphStyle('TOC 1', ['indentation' => ['firstLine' => 0, 'left' => 0]]);
        $phpWord->addParagraphStyle('TOC 2', ['indentation' => ['firstLine' => 0, 'left' => 360]]);

        // ── Section 1: Cover (no page number) ─────────────────────────────
        $coverSection = $phpWord->addSection(array_merge($pageSettings, ['pageSuppressNumbers' => true]));
        $this->addCoverPage($phpWord, $coverSection, $makalah);

        // ── Section 2: Front matter (romawi) ──────────────────────────────
        $frontSection = $phpWord->addSection(array_merge($pageSettings, [
            'pageNumberingStart' => 1,
            'pageNumberFormat'   => 'lowerRoman',
        ]));
        
        $frontFooter = $frontSection->addFooter();
        $frontFooter->addPreserveText('{PAGE \* ROMAN}', ['name' => 'Times New Roman', 'size' => 11], ['alignment' => Jc::CENTER]);
        
        $this->addFrontMatter($phpWord, $frontSection, $makalah);

        // ── Section 3: Isi (angka arab) ───────────────────────────────────
        $mainSection = $phpWord->addSection(array_merge($pageSettings, [
            'pageNumberingStart' => 1,
            'pageNumberFormat'   => 'decimal',
            'headerType'         => 'default',
        ]));
        
        $mainFooter = $mainSection->addFooter();
        $mainFooter->addPreserveText('{PAGE}', ['name' => 'Times New Roman', 'size' => 11], ['alignment' => Jc::CENTER]);
        
        $this->addChapters($phpWord, $mainSection, $makalah);
        $this->addReferences($phpWord, $mainSection, $makalah);

        // ── Simpan ke temp file ────────────────────────────────────────────
        $filename = 'Makalah_' . \Str::slug($makalah->judul) . '_' . now()->format('Ymd_His') . '.docx';
        $tempPath = storage_path('app/temp/' . $filename);

        if (! is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($tempPath);

        return $tempPath;
    }

    // ── Private: Word helpers ─────────────────────────────────────────────────

    private function addCoverText($section, string $text, int $size = 12, bool $bold = true, int $spaceAfter = 1): void
    {
        $section->addText($text, ['bold' => $bold, 'name' => 'Times New Roman', 'size' => $size], [
            'alignment' => Jc::CENTER, 
            'spaceAfter' => $spaceAfter * 240,
            'indentation' => ['left' => 0, 'right' => 0, 'firstLine' => 0]
        ]);
    }

    private function addCoverPage(PhpWord $phpWord, $section, Makalah $makalah): void
    {
        // 1. Jenis Dokumen (Size 16) - Match Frontend
        $this->addCoverText($section, strtoupper($makalah->jenis_dokumen ?: 'MAKALAH'), 16, true, 0);

        // 2. Judul (Size 20)
        $section->addTextBreak(1);
        $this->addCoverText($section, strtoupper($makalah->judul), 20, true, 0);
        if ($makalah->sub_judul) {
            $this->addCoverText($section, strtoupper($makalah->sub_judul), 16, true, 0);
        }

        $section->addTextBreak(1);

        // 3. Logo
        if ($makalah->logo_path && file_exists(public_path($makalah->logo_path))) {
            $section->addImage(
                public_path($makalah->logo_path),
                ['width' => 230, 'height' => 230, 'alignment' => Jc::CENTER]
            );
        }

        $section->addTextBreak(1);

        // 4. Info akademik (Size 12)
        if ($makalah->mata_kuliah) {
            $this->addCoverText($section, 'Disusun untuk Memenuhi Tugas Mata Kuliah', 12, false, 0);
            $this->addCoverText($section, $makalah->mata_kuliah, 12, false, 0);
            $section->addTextBreak(1);
        }
        if ($makalah->nama_dosen) {
            $this->addCoverText($section, 'Dosen Pengampu : ' . $makalah->nama_dosen, 12, false, 0);
        }

        $section->addTextBreak(1);

        // 5. Disusun oleh (Size 12)
        $this->addCoverText($section, 'Disusun Oleh :', 12, true, 0);
        $this->addCoverText($section, $makalah->nama_penulis, 12, false, 0);
        if ($makalah->nim) {
            $nimText = stripos(trim($makalah->nim), 'NIM') === 0 ? $makalah->nim : 'NIM : ' . $makalah->nim;
            $this->addCoverText($section, $nimText, 12, false, 0);
        }

        $section->addTextBreak(1);

        // 6. Institusi (Size 14) - Pinned to Footer
        $footer = $section->addFooter();
        
        $prodi = $makalah->prodi ?: 'PROGRAM STUDI EKONOMI PEMBANGUNAN';
        $universitas = $makalah->universitas ?: 'UNIVERSITAS TRILOGI';
        $tahun = $makalah->tahun ?: date('Y');

        $prodiText = stripos($prodi, 'program studi') === false 
            ? 'PROGRAM STUDI ' . $prodi 
            : $prodi;
        
        $this->addCoverText($footer, strtoupper($prodiText), 14, true, 1);
        
        if ($makalah->fakultas) {
            $this->addCoverText($footer, strtoupper($makalah->fakultas), 14, true, 1);
        }
        
        $this->addCoverText($footer, strtoupper($universitas), 14, true, 1);
        $this->addCoverText($footer, strtoupper(trim(($makalah->kota ? $makalah->kota . ' ' : '') . $tahun)), 14, true, 0);

        $section->addPageBreak();
    }


    private function addFrontMatter(PhpWord $phpWord, $section, Makalah $makalah): void
    {
        // Kata Pengantar
        if ($makalah->kata_pengantar) {
            $section->addTitle('KATA PENGANTAR', 1);
            $section->addTextBreak(1);
            $this->addHtmlContent($section, $makalah->kata_pengantar);
            $section->addPageBreak();
        }

        // Daftar Isi
        $section->addTitle('DAFTAR ISI', 1);
        $section->addTextBreak(1);
        $this->addTableOfContents($phpWord, $section, $makalah);
        $section->addPageBreak();
    }

    private function addTableOfContents(PhpWord $phpWord, $section, Makalah $makalah): void
    {
        // PHPWord generates a proper Word TOC field ({TOC \o "1-2" \h \z \u}).
        // Because we set setUpdateFields(true) on the document, Word will
        // automatically calculate and fill in the REAL page numbers the moment
        // the file is opened — no manual F9 needed by the user.
        $section->addTOC(
            // Font style for TOC entries
            ['name' => 'Times New Roman', 'size' => 12],
            // TOC style: dot leader, no hyperlink underlines
            ['tabLeader' => 'dot'],
            1, // min heading level
            2  // max heading level
        );
    }

    private function addChapters(PhpWord $phpWord, $section, Makalah $makalah): void
    {
        $babs = $makalah->chapters()->where('type', 'bab')->orderBy('order')->get();
        foreach ($babs as $bab) {
            // Bab heading (centered, bold, uppercase)
            $section->addTitle($bab->bab_label, 1);
            $section->addTitle(strtoupper($bab->title), 1);
            $section->addTextBreak(1);

            $subchapters = $bab->subchapters()->orderBy('order')->get();
            foreach ($subchapters as $i => $sub) {
                if ($sub->content) {
                    if ($i > 0) {
                        $section->addTextBreak(1);
                    }
                    $section->addTitle($bab->bab_number . '.' . ($i + 1) . ' ' . $sub->title, 2);
                    $section->addTextBreak(1);
                    $this->addHtmlContent($section, $sub->content);
                }
            }

            $section->addPageBreak();
        }
    }

    private function addReferences(PhpWord $phpWord, $section, Makalah $makalah): void
    {
        if ($makalah->references->isEmpty()) return;

        $section->addTitle('DAFTAR PUSTAKA', 1);
        $section->addTextBreak(1);

        // Sort alphabetically by penulis
        $sorted = $makalah->references->sortBy('penulis');
        foreach ($sorted as $ref) {
            // Hanging indent style
            $pStyle = ['hanging' => 720, 'spaceAfter' => 240, 'alignment' => Jc::BOTH];
            $fStyle = ['name' => 'Times New Roman', 'size' => 12];
            $section->addText(strip_tags($ref->raw_citation ?? ''), $fStyle, $pStyle);
        }
    }

    // ── Text helpers ───────────────────────────────────────────────────────────
    private function addCentered($section, string $text): void
    {
        $section->addText($text, ['name' => 'Times New Roman', 'size' => 12], ['alignment' => Jc::CENTER]);
    }

    private function addCenteredBold($section, string $text): void
    {
        $section->addText($text, ['name' => 'Times New Roman', 'size' => 12, 'bold' => true], ['alignment' => Jc::CENTER]);
    }

    private function addHtmlContent($section, string $html): void
    {
        // Tag tokens for lists
        $html = preg_replace_callback('/<ol[^>]*>(.*?)<\/ol>/is', function($m) {
            return preg_replace_callback('/<li[^>]*>(.*?)<\/li>/is', function($li) {
                $text = preg_replace('/^\s*(?:<[^>]+>\s*)*(?:[-\x{2022}*]|\d+[\.\)])\s*/u', '', $li[1]);
                return "[NUM] " . trim($text) . "\n";
            }, $m[1]);
        }, $html);

        $html = preg_replace_callback('/<ul[^>]*>(.*?)<\/ul>/is', function($m) {
            return preg_replace_callback('/<li[^>]*>(.*?)<\/li>/is', function($li) {
                $text = preg_replace('/^\s*(?:<[^>]+>\s*)*(?:[-\x{2022}*]|\d+[\.\)])\s*/u', '', $li[1]);
                return "[BUL] " . trim($text) . "\n";
            }, $m[1]);
        }, $html);

        // Strip block tags, split by <p> or <br>
        $html = str_replace(['</p>', '<br>', '<br/>'], "\n", $html);
        $html = strip_tags($html, '<strong><em><b><i>');

        $paragraphs = array_filter(explode("\n", $html));
        $fStyle     = ['name' => 'Times New Roman', 'size' => 12];
        
        $pStyle = [
            'lineHeight' => 1.5, 
            'alignment' => Jc::BOTH, 
            'spaceAfter' => 240, // 12pt space after paragraph
            'indentation' => ['firstLine' => 720]
        ];
        
        $listPStyle = [
            'lineHeight' => 1.5, 
            'alignment' => Jc::BOTH, 
            'spaceAfter' => 120 // 6pt space after list item
        ];

        foreach ($paragraphs as $para) {
            $para = trim($para);
            if ($para === '') continue;
            
            $isNum = str_starts_with($para, '[NUM] ');
            $isBul = str_starts_with($para, '[BUL] ');
            
            // Check for manual bullets/numbers in paragraphs
            $isManualNum = preg_match('/^(\d+\.)\s+(.*)/us', strip_tags($para));
            $isManualBul = preg_match('/^([-\x{2022}*])\s+(.*)/us', strip_tags($para));

            $isList = $isNum || $isBul || $isManualNum || $isManualBul;

            if ($isList) {
                if ($isNum || $isBul) {
                    $para = substr($para, 6);
                    $listType = $isNum ? \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED : \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED;
                } else if ($isManualNum) {
                    $para = preg_replace('/^(?:\s*<[^>]+>\s*)*\d+\.\s*/us', '', $para);
                    $listType = \PhpOffice\PhpWord\Style\ListItem::TYPE_NUMBER_NESTED;
                } else {
                    $para = preg_replace('/^(?:\s*<[^>]+>\s*)*[-\x{2022}*]\s*/us', '', $para);
                    $listType = \PhpOffice\PhpWord\Style\ListItem::TYPE_BULLET_FILLED;
                }
            }

            // Cek apakah ada footnote format ((isi footnote))
            if (str_contains($para, '((') && str_contains($para, '))')) {
                if ($isList) {
                    $textRun = $section->addListItemRun(0, $listType, $listPStyle);
                } else {
                    $textRun = $section->addTextRun($pStyle);
                }
                
                $parts = preg_split('/\(\((.*?)\)\)/', $para, -1, PREG_SPLIT_DELIM_CAPTURE);
                foreach ($parts as $index => $part) {
                    if ($index % 2 === 0) {
                        if ($part !== '') {
                            $textRun->addText(strip_tags($part), $fStyle);
                        }
                    } else {
                        $footnote = $textRun->addFootnote();
                        $footnote->addText(strip_tags($part), ['name' => 'Times New Roman', 'size' => 10]);
                    }
                }
            } else {
                if ($isList) {
                    $section->addListItem(strip_tags($para), 0, $fStyle, $listType, $listPStyle);
                } else {
                    $section->addText(strip_tags($para), $fStyle, $pStyle);
                }
            }
        }
    }

    private function toRoman(int $n): string
    {
        $map    = [1000=>'M',900=>'CM',500=>'D',400=>'CD',100=>'C',90=>'XC',50=>'L',40=>'XL',10=>'X',9=>'IX',5=>'V',4=>'IV',1=>'I'];
        $result = '';
        foreach ($map as $value => $numeral) {
            while ($n >= $value) { $result .= $numeral; $n -= $value; }
        }
        return $result;
    }
}
