<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

// Show the chat page.
Route::get('/', [ChatController::class, 'index']);

// Receive messages from the browser and return the assistant's reply.
Route::post('/chat', [ChatController::class, 'send'])->name('chat.send');