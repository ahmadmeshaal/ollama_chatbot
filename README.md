# Laravel Ollama Chat

A minimal Laravel chatbot. The browser sends one message to Laravel, and Laravel asks Ollama for a reply. It does not use a database or save conversation history.

## Requirements

- PHP 8.3 or later
- Composer
- Ollama running locally with the `qwen3:8b` model installed

## Run on Windows

1. Open PowerShell and enter the project folder:

   ```powershell
   cd C:\xampp\htdocs\ollama_chatbot_laravel
   ```

2. Start the Laravel development server:

   ```powershell
   php artisan serve
   ```

3. Open `http://127.0.0.1:8000` in your browser. Keep Ollama running while using the chatbot.

If your installed model has a different name, update `OLLAMA_MODEL` in `.env`.

## Main files

- `routes/web.php`: page and chat routes.
- `app/Http/Controllers/ChatController.php`: validates messages and calls Ollama.
- `config/ollama.php`: reads the Ollama settings from `.env`.
- `resources/views/chat.blade.php`: chat page and browser JavaScript.
- `.env`: Ollama URL and model name.

Laravel sends requests to `http://127.0.0.1:11434/api/chat`. Laravel and Ollama must run on the same computer for this local setup.