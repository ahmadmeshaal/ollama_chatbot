<?php

return [
    // These values can be changed in the project's .env file.
    'url' => env('OLLAMA_URL', 'http://127.0.0.1:11434/api/chat'),
    'model' => env('OLLAMA_MODEL', 'qwen3:8b'),
];