<?php

namespace App\Notifier;

use Symfony\Component\Notifier\Recipient\RecipientInterface;

interface BoustigrailleRecipientInterface extends RecipientInterface
{
    public function getId(): int;
}
