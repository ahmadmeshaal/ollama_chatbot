<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel Ollama Chat</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 20px; background: #f6f1ed; color: #302b2a; font-family: Arial, sans-serif; }
        main { width: min(100%, 760px); height: min(780px, 94vh); display: flex; flex-direction: column; background: #fffdfa; border: 1px solid #eadfd9; border-radius: 24px; overflow: hidden; box-shadow: 0 24px 70px #52372a18; }
        header { padding: 22px 26px; border-bottom: 1px solid #eadfd9; }
        header small { color: #744c52; letter-spacing: .16em; font-weight: bold; }
        h1 { margin: 8px 0 0; font-size: 25px; }
        #chat { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 14px; }
        .bubble { max-width: 85%; padding: 14px 16px; border-radius: 16px; line-height: 1.55; white-space: pre-wrap; overflow-wrap: anywhere; }
        .assistant { align-self: flex-start; background: #f6f1ed; border-bottom-left-radius: 4px; }
        .user { align-self: flex-end; background: #744c52; color: white; border-bottom-right-radius: 4px; }
        form { display: flex; gap: 10px; padding: 16px; border-top: 1px solid #eadfd9; }
        input { flex: 1; min-width: 0; padding: 14px; border: 1px solid #eadfd9; border-radius: 12px; font-size: 16px; }
        button { padding: 0 20px; border: 0; border-radius: 12px; background: #744c52; color: white; font-weight: bold; cursor: pointer; }
        button:disabled { opacity: .6; cursor: wait; }
        #status { margin: 0; padding: 0 18px 13px; color: #867b76; font-size: 12px; }
        @media (max-width: 520px) { body { padding: 0; } main { height: 100vh; border: 0; border-radius: 0; } header { padding: 18px; } #chat { padding: 18px; } form { padding: 12px; } button { padding: 0 14px; } }
    </style>
</head>
<body>
<main>
    <header>
        <small>LOCAL AI · OLLAMA</small>
        <h1>Chat assistant</h1>
    </header>
    <section id="chat" aria-live="polite">
        <div class="bubble assistant">Hi! Ask me anything.</div>
    </section>
    <p id="status">Connected to your local Ollama setup.</p>
    <form id="chat-form">
        <input id="message" type="text" maxlength="1000" placeholder="Write a message..." autocomplete="off" required>
        <button id="send" type="submit">Send</button>
    </form>
</main>
<script>
    // Send each message to Laravel and display the returned reply.
    const form = document.querySelector('#chat-form');
    const input = document.querySelector('#message');
    const chat = document.querySelector('#chat');
    const button = document.querySelector('#send');
    const statusText = document.querySelector('#status');

    function addMessage(text, sender) {
        const bubble = document.createElement('div');
        bubble.className = `bubble ${sender}`;
        bubble.textContent = text;
        chat.append(bubble);
        chat.scrollTop = chat.scrollHeight;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const message = input.value.trim();
        if (!message) return;

        addMessage(message, 'user');
        input.value = '';
        button.disabled = true;
        statusText.textContent = 'Thinking…';

        try {
            const response = await fetch('{{ route('chat.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message }),
            });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Request failed.');
            addMessage(result.message, 'assistant');
            statusText.textContent = 'Connected to local Ollama.';
        } catch (error) {
            addMessage(error.message, 'assistant');
            statusText.textContent = 'Check that Laravel and Ollama are running.';
        } finally {
            button.disabled = false;
            input.focus();
        }
    });
</script>
</body>
</html>