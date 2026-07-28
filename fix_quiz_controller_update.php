<?php
$content = file_get_contents('app/Http/Controllers/Admin/QuizController.php');
$content = preg_replace(
    "/\\\$validated = \\\$request->validate\(\\[\s*DB::transaction\(function \(\) use \(\\\$validated, \\\$quiz\) \{/",
    "\$validated = \$request->validate([\n            'title' => 'required|string|max:255',\n            'subject_id' => 'required|exists:subjects,id',\n            'material_id' => 'nullable|exists:materials,id',\n            'target_users' => 'nullable|array',\n            'target_users.*' => 'exists:users,id',\n            'time_limit_minutes' => 'nullable|integer|min:1',\n            'questions' => 'nullable|array',\n            'questions.*.id' => 'nullable|exists:quiz_questions,id',\n            'questions.*.type' => 'nullable|in:multiple_choice,essay',\n            'questions.*.question' => 'nullable|string',\n            'questions.*.options' => 'nullable|array|size:4',\n            'questions.*.options.*' => 'nullable|string',\n            'questions.*.correct_answer' => 'nullable|integer|min:0|max:3',\n            'questions.*.explanation' => 'nullable|string',\n        ]);\n\n        DB::transaction(function () use (\$validated, \$quiz) {",
    $content
);
file_put_contents('app/Http/Controllers/Admin/QuizController.php', $content);
