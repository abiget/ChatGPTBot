<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $question = request('question');
            
            $reponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAP_API_KEY'),
            ])->post('https://api.openai.com/v1/chat/completions', [
                "model" => "gpt-3.5-turbo",
                "messages" => [["role" => "user", "content" => "$question"]],
                "temperature" => 0.7
            ]);

            $chat_response = $reponse->json()['choices'][0]['message']['content'];

            return response()->json(['message' => $chat_response]);
        }
    }
}
