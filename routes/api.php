<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route to post a message to a chat room
Route::post('/{roomId}', function ($roomId, Request $request) {
    // Validate or sanitize input as needed
    $author = trim($request->input('author')); // Trim to remove unwanted whitespaces
    $message = trim($request->input('message'));

    if (empty($author) || empty($message)) {
        // You may want to return an error response if author or message is empty
        return response()->json(['status' => 'error', 'message' => 'Author and message are required.'], 400);
    }

    // Create a timestamp for the message
    $timestamp = now()->toDateTimeString();

    // Construct the message line with the author and timestamp
    $messageLine = "{$timestamp} | {$author} | {$message}\n";

    // Append the message to the room's file
    Storage::append("rooms/{$roomId}.txt", $messageLine);

    // Respond back with a success message
    return response()->json(['status' => 'success', 'message' => 'Message posted']);
});

// Route to get messages from a chat room
Route::get('/{roomId}', function ($roomId) {
    // Check if the room's file exists
    if (Storage::exists("rooms/{$roomId}.txt")) {
        // Get the room's messages
        $fileContents = Storage::get("rooms/{$roomId}.txt");
        $lines = explode("\n", $fileContents);

        $messages = array_map(function($line) {
            // Split each line by " | " to separate timestamp, author, and message
            $parts = explode(" | ", $line, 3);
            if(count($parts) === 3) {
                // Return structured message data
                return [
                    'timestamp' => $parts[0],
                    'author'    => $parts[1],
                    'message'   => $parts[2],
                ];
            }
            return null; // Return null for lines that do not conform to expected format
        }, $lines);

        // Remove any null values that may have resulted from malformed lines
        $messages = array_filter($messages);
    } else {
        // If the file doesn't exist, return an empty array of messages
        return response()->json(['status' => 'error', 'message' => 'Room not found'], 404);
    }
    // Respond with the messages
    return response()->json(['status' => 'success', 'messages' => $messages]);
});

