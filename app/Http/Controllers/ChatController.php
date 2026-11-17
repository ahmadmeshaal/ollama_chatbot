<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatController extends Controller
{
    // Display the chat interface.
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            // Ask the configured local Ollama model to answer the message.
            $response = Http::timeout(120)->post(config('ollama.url'), [
                'model' => config('ollama.model'),
                'messages' => [
                    ['role' => 'user', 'content' => $data['message']],
                ],
                'stream' => false,
            ]);
        } catch (\Throwable $error) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot connect to Ollama. Make sure it is running.',
            ], 503);
        }

        // Return Ollama's error if the request was unsuccessful.
        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => $response->json('error') ?? 'Ollama returned an error.',
            ], 502);
        }

        // Extract the assistant's text from Ollama's JSON response.
        $answer = $response->json('message.content');

        if (!$answer) {
            return response()->json([
                'success' => false,
                'message' => 'Ollama returned no answer.',
            ], 502);
        }

        // Send the answer back to the browser as JSON.
        return response()->json([
            'success' => true,
            'message' => $answer,
        ]);
    }
}