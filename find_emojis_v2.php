<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$emojis = [];
foreach($files as $file) {
    $content = file_get_contents($file[0]);
    // Match common emojis
    preg_match_all('/[\x{1F300}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}\x{1FA70}-\x{1FAFF}]/u', $content, $matches);
    if (!empty($matches[0])) {
        foreach($matches[0] as $match) {
            $emojis[$match][] = $file[0];
        }
    }
}

foreach($emojis as $emoji => $paths) {
    echo $emoji . " => " . count(array_unique($paths)) . " files\n";
}
