<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenAI;

class ChatBotController extends Controller
{
    public function index()
    {
        $question = request()->query('question');

        $client = OpenAI::client(env('OPENAP_API_KEY'));

        return response()->stream(function () use ($question, $client) {
            $stream = $client->chat()->createStreamed([
                "model" => "gpt-3.5-turbo",
                "messages" => [
                    ["role" => "user", "content" => "$question"]
                ],
                'max_tokens' => 1024,
            ]);

            foreach ($stream as $response) {
                $text = $response->choices[0]->toArray()['delta']['content'];
                
                if (connection_aborted()) {
                    break;
                }

                echo "event: update\n";
                echo 'data: ' . $text;
                echo "\n\n";
                ob_flush();
                flush();
            }

            echo "event: update\n";
            echo 'data: <end>';
            echo "\n\n";
            ob_flush();
            flush();
        }, 200, [
            'Cache-Control' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Content-Type' => 'text/event-stream',
        ]);

    }
}
