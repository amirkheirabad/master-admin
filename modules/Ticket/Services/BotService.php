<?php

namespace Modules\Ticket\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BotService
{
    private $botToken = '815696719:ZmmvEEpeYhw5h5UWOa9yjI_F-I1goMKNAHc';

    private $chatOrderId = '5412710583';

    private $baseUrl = 'https://tapi.bale.ai';

    public function sendMessage($message, $uri = 'sendMessage')
    {
        $body['chat_id'] = $this->chatOrderId;
        $body['text'] = $message;
        $response = Http::asForm()->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'multipart/form-data;',
        ])->post(
            "$this->baseUrl/$this->botToken/$uri", $body
        );

    }

    public function sendTicketMessage($sender, $textMessage)
    {
        $message = "
        پیام جدید تیکت
        فرستنده: {$sender}
        متن پیام: {$textMessage}
        ";

        $this->sendMessage($message);
    }
}
