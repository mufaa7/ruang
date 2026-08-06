<?php
$files = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php',
    'resources/views/layouts/admin.blade.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // Remove old font links
    $content = preg_replace('/<link[^>]*family=(?:Cormorant\+Garamond|Instrument\+Serif|Source\+Serif)[^>]*>/i', '', $content);
    $content = preg_replace('/<link[^>]*geist-sans[^>]*>/i', '', $content);
    $content = preg_replace('/<style>.*?@font-face\s*{[^}]*\'Geist\'[^}]*}[^}]*<\/style>/is', '', $content);
    
    // Replace hardcoded body background with nothing or Tailwind class
    $content = str_replace('bg-[#F7F5F1] dark:bg-slate-950', 'bg-[var(--ruang-canvas)]', $content);
    
    // Replace sidebar background with liquid glass
    $content = str_replace('bg-[#EFECE5] dark:bg-slate-950', 'liquid-glass', $content);

    // Remove inline styles setting font-family to serif
    $content = preg_replace('/style="font-family:\s*\'Cormorant Garamond\'[^"]*"/i', '', $content);
    
    file_put_contents($file, $content);
    echo "Cleaned $file\n";
}
