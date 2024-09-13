<?php

namespace App\Notifier;

use App\Entity\User;
use Symfony\Component\Notifier\Notification\Notification;

class BoustigrailleNotification extends Notification
{
    public function __construct(
        private User $userRecipient,
        string $subject = '',
        array $channels = []
    ) {
        parent::__construct($subject, $channels);
    }
}