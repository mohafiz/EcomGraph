<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    function handleTelegramUpdates(Request $request)
    {
        $update  = $request->json()->all();

        $message = $update['message']['text'];
        $chatId = $update['message']['chat']['id'];

        if ($message == '/start')
            $this->sendMessage($chatId, "Hello there!\n Please use the /subscribe from the menu to subscribe yourself, or user /complete if you have already registered via the system. In either case, you will be able to receive your order status notifications here once you completed the process");

        if ($message == '/subscribe') {

            $user = User::where('chat_id', $chatId)->first();

            if ($user)
                $this->sendMessage($chatId, "You are already subscribed with us");
            else {
                User::create(['chat_id' => $chatId, 'role' => 2, 'subscribed' => true]);
                $this->sendMessage($chatId, "This is your telegram ID: $chatId, please use it when you register on the website to receive notification via telegram");
            }
        }

        if ($message == '/complete')
            $this->sendMessage($chatId, "Please provide your email that you register with, in the form of email: example@email.com");

        if (preg_match('/^email: [a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $message) === 1) {
            
            $email = explode(': ', $message)[1];
            $user = User::where('email', $email)->first();

            if (!$user || !$user->verified) {
                $this->sendMessage($chatId, 'please check your email address');
            }
            else {
                $user->update(['subscribed' => true]);
                $this->sendMessage($chatId, 'You have successfully completed your registration, you can now login to the system');
            }
        }
    }

    function sendMessage($chatId, $content)
    {
        try {
            $token = config('services.telegram-bot-api.token');
            $url   = "https://api.telegram.org/bot$token/sendMessage";

            $data = [
                'chat_id' => $chatId,
                'text' => $content
            ];

            Http::post($url, $data);

        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
