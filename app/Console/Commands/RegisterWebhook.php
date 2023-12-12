<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class RegisterWebhook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webhook:register {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register a webhook for receiving telegram bot updates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $webhookUrl = $this->argument('url');

        $token = config('services.telegram-bot-api.token');
        $url   = "https://api.telegram.org/bot$token/setWebhook?url=$webhookUrl";

        Http::get($url);
    }
}
