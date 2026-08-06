<?php
$css = file_get_contents("https://cdn-uicons.flaticon.com/2.1.0/uicons-solid-rounded/css/uicons-solid-rounded.css");
preg_match_all("/\.fi-sr-([a-z0-9-]+):before/", $css, $matches);
$classes = array_unique($matches[1]);
file_put_contents("all_uicons.json", json_encode(array_values($classes), JSON_PRETTY_PRINT));
echo count($classes) . " UIcons found.\n";
