<?php

namespace App\Providers;

use App\Services\WhatsApp\BaileysWhatsAppGateway;
use App\Services\WhatsApp\LogWhatsAppGateway;
use App\Services\WhatsApp\WhatsAppGateway;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(WhatsAppGateway::class, function () {
            return config('services.whatsapp.driver') === 'baileys'
                ? new BaileysWhatsAppGateway
                : new LogWhatsAppGateway;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
