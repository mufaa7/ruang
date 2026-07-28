<?php
$content = file_get_contents('app/Http/Controllers/Admin/QuizController.php');
$content = str_replace(
    "foreach (\$validated['questions'] as \$q) {\n                \$type = \$q['type'] ?? 'multiple_choice';",
    "foreach (\$validated['questions'] as \$q) {\n                if (empty(\$q['question'])) continue;\n                \$type = \$q['type'] ?? 'multiple_choice';",
    $content
);
$content = preg_replace(
    "/foreach \(\\\$validated\['questions'\] as \\\$q\) \{\s*\\\$type = \\\$q\['type'\] \?\? 'multiple_choice';/",
    "foreach (\$validated['questions'] as \$q) {\n                if (empty(\$q['question'])) continue;\n                \$type = \$q['type'] ?? 'multiple_choice';",
    $content
);
file_put_contents('app/Http/Controllers/Admin/QuizController.php', $content);
