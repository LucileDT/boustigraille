<?php

namespace App\Migrations\Interfaces;

use App\Service\Migrations\NotificationService;

interface Notifiable
{
    public function setNotificationService(NotificationService $notificationService);
}
