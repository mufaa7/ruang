<?php
$json = json_decode(file_get_contents('models.json'), true);
$names = [];
if (isset($json['models'])) {
    foreach ($json['models'] as $m) {
        $names[] = $m['name'];
    }
}
file_put_contents('model_names.txt', implode("\n", $names));
