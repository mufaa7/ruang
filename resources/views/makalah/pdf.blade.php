<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $makalah->judul }}</title>
    <style>
        @page {
            margin: {{ $margin_top }}mm {{ $margin_right }}mm {{ $margin_bottom }}mm {{ $margin_left }}mm;
        }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: {{ $font_size }}pt;
            line-height: {{ $line_height }};
            color: #000000;
        }
        /* Reset ALL link colors so HTML content links don't turn blue/red */
        a, a:link, a:visited, a:hover, a:active {
            color: #000000 !important;
            text-decoration: none !important;
        }
        .page-break {
            page-break-after: always;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        h1, h2, h3, h4, h5, h6 {
            font-family: "Times New Roman", Times, serif;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 12pt;
            text-align: center;
            font-size: {{ $font_size }}pt;
        }
        p {
            margin-top: 0;
            margin-bottom: 0;
            text-align: justify;
        }

        .chapter p {
            text-indent: 1.27cm;
        }
    </style>
</head>
<body>

    <!-- Header & Footer for DOMPDF -->
    <script type="text/php">
        if ( isset($pdf) ) {
            $font = $fontMetrics->get_font("Times New Roman", "normal");
            $size = 11;
            // Center page numbers at the bottom
            // X = Center of page, Y = Bottom edge - margin
            $y = $pdf->get_height() - 40;
            $x = $pdf->get_width() / 2 - 4;
            $pdf->page_text($x, $y, "{PAGE_NUM}", $font, $size, array(0,0,0));
        }
    </script>

    <!-- COVER PAGE -->
    <div style="position: relative; height: 97vh; text-align: center; font-family: 'Times New Roman', serif;">

        {{-- Jenis Dokumen --}}
        <p style="font-size: 16pt; font-weight: bold; text-transform: uppercase; text-align: center; margin-top: 0; margin-bottom: 0;">
            {{ $makalah->jenis_dokumen ?: 'MAKALAH' }}
        </p>

        {{-- Judul + Sub Judul --}}
        <p style="font-size: 20pt; font-weight: bold; text-transform: uppercase; text-align: center; margin-top: 14pt; margin-bottom: 0;">
            {{ $makalah->judul }}
        </p>
        @if($makalah->sub_judul)
            <p style="font-size: 16pt; font-weight: bold; text-transform: uppercase; text-align: center; margin-top: 4pt; margin-bottom: 0;">
                {{ $makalah->sub_judul }}
            </p>
        @endif

        {{-- Logo --}}
        <div style="margin-top: 28pt; margin-bottom: 28pt; text-align: center;">
            @if($makalah->logo_path && file_exists(public_path($makalah->logo_path)))
                @php
                    $logoPath = public_path($makalah->logo_path);
                    $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $logoData = file_get_contents($logoPath);
                    $logoBase64 = 'data:image/' . $logoType . ';base64,' . base64_encode($logoData);
                @endphp
                <img
                    src="{{ $logoBase64 }}"
                    style="display: block; margin: 0 auto; width: 380px; height: 380px;"
                    alt="Logo">
            @else
                <div style="height: 380px;"></div>
            @endif
        </div>

        {{-- Matkul & Dosen --}}
        <div style="font-size: 12pt; text-align: center;">
            @if($makalah->mata_kuliah)
                <p style="margin-top: 0; margin-bottom: 0; text-align: center;">Disusun untuk Memenuhi Tugas Mata Kuliah</p>
                <p style="margin-top: 0; margin-bottom: 10pt; text-align: center;">{{ $makalah->mata_kuliah }}</p>
            @endif
            @if($makalah->nama_dosen)
                <p style="margin-top: 0; margin-bottom: 0; text-align: center;">Dosen Pengampu : {{ $makalah->nama_dosen }}</p>
            @endif
        </div>

        {{-- Penulis --}}
        <div style="margin-top: 14pt; font-size: 12pt; text-align: center;">
            <p style="font-weight: bold; margin-top: 0; margin-bottom: 0; text-align: center;">Disusun Oleh :</p>
            <p style="margin-top: 0; margin-bottom: 0; text-align: center;">{{ $makalah->nama_penulis }}</p>
            @if($makalah->nim)
                @php
                    $nimText = stripos(trim($makalah->nim), 'NIM') === 0 ? $makalah->nim : 'NIM : ' . $makalah->nim;
                @endphp
                <p style="margin-top: 0; margin-bottom: 0; text-align: center;">{{ $nimText }}</p>
            @endif
        </div>

        {{-- Institusi (pinned to bottom) --}}
        <div style="position: absolute; bottom: 0; left: 0; right: 0; text-align: center; font-size: 14pt; font-weight: bold; text-transform: uppercase; line-height: 1.5;">
            @php
                $prodi      = $makalah->prodi      ?: 'PROGRAM STUDI EKONOMI PEMBANGUNAN';
                $universitas = $makalah->universitas ?: 'UNIVERSITAS TRILOGI';
                $tahun      = $makalah->tahun      ?: date('Y');
            @endphp
            <p style="margin-bottom: 0; text-align: center;">
                {{ stripos($prodi, 'program studi') === false ? 'PROGRAM STUDI ' . strtoupper($prodi) : strtoupper($prodi) }}
            </p>
            @if($makalah->fakultas)
                <p style="margin-bottom: 0; text-align: center;">{{ strtoupper($makalah->fakultas) }}</p>
            @endif
            <p style="margin-bottom: 0; text-align: center;">{{ strtoupper($universitas) }}</p>
            <p style="margin-top: 6pt; margin-bottom: 0; text-align: center;">
                {{ strtoupper(trim(($makalah->kota ? $makalah->kota . ' ' : '') . $tahun)) }}
            </p>
        </div>
    </div>

    <div class="page-break"></div>


    <!-- KATA PENGANTAR -->
    @if($makalah->kata_pengantar)
        <div class="chapter">
            <h1 class="uppercase" style="margin-bottom: 24pt;">KATA PENGANTAR</h1>
            <div>
                {!! $makalah->kata_pengantar !!}
            </div>
        </div>
        <div class="page-break"></div>
    @endif

    <!-- CHAPTERS -->
    @foreach($makalah->chapters->where('type', 'bab') as $bab)
        <div class="chapter">
            <h1 class="uppercase">{{ $bab->bab_label }}<br>{{ $bab->title }}</h1>
            
            <div style="margin-top: 24pt;">
                @foreach($bab->subchapters as $i => $sub)
                    @if($sub->content)
                        <h2 style="text-align: left; font-size: {{ $font_size }}pt; margin-top: 12pt;">
                            {{ $bab->bab_number }}.{{ $i+1 }} {{ $sub->title }}
                        </h2>
                        <div>
                            {!! preg_replace('/\(\((.*?)\)\)/', '<sup style="font-size:10px;vertical-align:super;">[$1]</sup>', $sub->content) !!}
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
        
        <div class="page-break"></div>
    @endforeach

    <!-- DAFTAR PUSTAKA -->
    @if($makalah->references->isNotEmpty())
        <div class="page-break"></div>
        <div class="chapter">
            <h1 class="uppercase">DAFTAR PUSTAKA</h1>
            <div style="padding-left: 20px; text-indent: -20px;">
                @foreach($makalah->references->sortBy('penulis') as $ref)
                    <p>{!! nl2br(e($ref->raw_citation)) !!}</p>
                @endforeach
            </div>
        </div>
    @endif

</body>
</html>
