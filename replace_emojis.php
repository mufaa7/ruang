<?php
$map = [
    '✔' => 'check',
    '✅' => 'check-circle',
    '✓' => 'check',
    '☑' => 'checkbox',
    '⚡' => 'bolt',
    '✕' => 'cross',
    '✖' => 'cross',
    '❌' => 'cross-circle',
    '✗' => 'cross',
    '🔌' => 'plug',
    '🔴' => 'circle',
    '🎵' => 'music-alt',
    '♫' => 'music-alt',
    '🚧' => 'triangle-warning',
    '🙂' => 'smile',
    '🗓️' => 'calendar',
    '🗓' => 'calendar',
    '✏️' => 'pencil',
    '✏' => 'pencil',
    '🖋️' => 'pen-nib',
    '🖋' => 'pen-nib',
    '✍️' => 'edit',
    '✍' => 'edit',
    '📄' => 'document',
    '📝' => 'memo',
    '📑' => 'document',
    '🧪' => 'flask',
    '📚' => 'books',
    '⚠️' => 'exclamation',
    '⚠' => 'exclamation',
    '👋' => 'hand-wave',
    '🗑️' => 'trash',
    '🗑' => 'trash',
    '✨' => 'sparkles',
    '🔄' => 'refresh',
    '💡' => 'bulb',
    '📘' => 'book',
    '🌐' => 'globe',
    '🖼️' => 'picture',
    '🖼' => 'picture',
    '📊' => 'chart-histogram',
    '⛔' => 'ban',
    '🚀' => 'rocket-lunch', // flaticon rocket is usually rocket-lunch or space-shuttle
    '🎉' => 'party-horn',
    '☕' => 'mug-hot',
    '📌' => 'marker',
    '🔒' => 'lock',
    '🏫' => 'school',
    '🌍' => 'earth-americas',
    '🚩' => 'flag',
    '🤷‍♂️' => 'user-unknown',
    '🤷' => 'user-unknown',
    '♂' => 'mars', // just in case
    '🏆' => 'trophy',
    '🔥' => 'flame',
    '😤' => 'angry',
    '💀' => 'skull',
    '🤦' => 'face-sad-sweat',
    '⏱️' => 'stopwatch',
    '⏱' => 'stopwatch'
];

$finalMap = [];
foreach($map as $emoji => $icon) {
    if ($icon === 'rocket-lunch') $icon = 'rocket'; // let's fallback to rocket
    if ($icon === 'earth-americas') $icon = 'globe';
    if ($icon === 'face-sad-sweat') $icon = 'sad';
    
    // Add text-xl or similar? No, just inherit font size.
    $finalMap[$emoji] = "<i class=\"fi fi-sr-$icon\"></i>";
}

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

foreach($files as $file) {
    $path = $file[0];
    
    // Skip admin files as requested "blade user"
    if (strpos($path, 'admin\\') !== false || strpos($path, 'admin/') !== false) continue;
    // Skip layouts/admin.blade.php
    if (strpos($path, 'layouts\\admin.blade.php') !== false || strpos($path, 'layouts/admin.blade.php') !== false) continue;

    $content = file_get_contents($path);
    
    // Convert .innerText to .innerHTML in JS so HTML icons work
    $content = str_replace('.innerText =', '.innerHTML =', $content);
    
    $newContent = str_replace(array_keys($finalMap), array_values($finalMap), $content);
    
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    }
}
