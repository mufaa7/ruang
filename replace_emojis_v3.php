<?php
$map = [
    '🔌' => 'plug',
    '✅' => 'check-circle',
    '🔴' => 'record',
    '✔' => 'check',
    '⚡' => 'lightning',
    '✕' => 'x',
    '❌' => 'x-circle',
    '♪' => 'music-notes',
    '♫' => 'music-notes-simple',
    '✨' => 'sparkle',
    '🗯' => 'chat-circle',
    '🍗' => 'bone',
    '🎤' => 'microphone',
    '🌀' => 'spiral',
    '🎵' => 'music-note',
    '🚧' => 'barricade',
    '🙂' => 'smiley',
    '⚠' => 'warning',
    '👋' => 'hand-waving',
    '🗑' => 'trash',
    '✏' => 'pencil',
    '🔄' => 'arrows-clockwise',
    '📑' => 'bookmark',
    '💡' => 'lightbulb',
    '📚' => 'books',
    '📘' => 'book',
    '📄' => 'file',
    '🌐' => 'globe',
    '🖼' => 'image',
    '📊' => 'chart-bar',
    '⛔' => 'prohibit',
    '✖' => 'x',
    '🚀' => 'rocket',
    '🎉' => 'confetti',
    '📝' => 'notepad',
    '☕' => 'coffee',
    '✍' => 'signature',
    '📌' => 'push-pin',
    '✓' => 'check',
    '🔒' => 'lock',
    '🏫' => 'buildings',
    '🌍' => 'globe-hemisphere-west',
    '🚩' => 'flag',
    '🤷' => 'question',
    '♂' => 'gender-male',
    '🏆' => 'trophy',
    '🔥' => 'fire',
    '😤' => 'sneezing', // close enough
    '💀' => 'skull',
    '🤦' => 'face-palm',
    '✗' => 'x',
    '⚡️' => 'lightning', // variant
    '✏️' => 'pencil',
    '📄' => 'file',
    '🖋️' => 'pen-nib',
    '🧪' => 'flask',
    '🗓️' => 'calendar'
];

$finalMap = [];
foreach($map as $emoji => $icon) {
    // We'll wrap them in a standard inline block if needed, but <i class="ph..."></i> is best
    $finalMap[$emoji] = "<i class=\"ph ph-$icon text-[1.1em] align-middle\"></i>";
}

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$count = 0;
foreach($files as $file) {
    $path = $file[0];
    
    // Skip admin files to keep them safe if needed, but user said "semua blade user".
    if (strpos($path, 'admin\\') !== false || strpos($path, 'admin/') !== false) continue;
    
    $content = file_get_contents($path);
    
    $newContent = str_replace(array_keys($finalMap), array_values($finalMap), $content);
    
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
        $count++;
    }
}
echo "Total updated: $count\n";
