<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\WhatsAppService;

class SendOrderWhatsAppNotification
{
    protected $whatsAppService;

    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }

    public function handle(OrderCreated $event)
    {
        try {
            $this->whatsAppService->sendOrderMessage($event->order);
        } catch (\Throwable $e) {
            // diamkan error agar tidak mengganggu proses utama
        }
    }
}
