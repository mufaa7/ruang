<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);
foreach($files as $file) {
    $content = file_get_contents($file[0]);
    if (preg_match_all('/[\x{1F300}-\x{1F6FF}\x{1F900}-\x{1F9FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F1E6}-\x{1F1FF}]/u', $content, $matches)) {
        $emojis = array_unique($matches[0]);
        echo $file[0] . ": " . implode(" ", $emojis) . "\n";
    }
}
