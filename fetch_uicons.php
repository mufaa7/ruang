<?php
$css = file_get_contents("https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css");
preg_match_all("/\.fi-sr-([a-z0-9-]+):before/", $css, $matches);
$classes = array_unique($matches[1]);
$filtered = array_filter($classes, function($c) {
    return preg_match("/(moon|sleep|music|dot|angry|sad|tired|star|sparkle|comment|message|burger|food|drumstick|meat|chicken|mic|spin|rotate|brain)/", $c);
});
file_put_contents("uicons.json", json_encode(array_values($filtered), JSON_PRETTY_PRINT));
echo "Done.";
