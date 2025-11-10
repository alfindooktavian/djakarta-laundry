<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Services\WhatsAppService;

class SendOrderCompletionWhatsAppNotification
{
    protected WhatsAppService $waService;

    public function __construct(WhatsAppService $waService)
    {
        $this->waService = $waService;
    }

    public function handle(OrderCompleted $event): void
    {
        $this->waService->sendOrderCompletedMessage($event->order);
    }
}
