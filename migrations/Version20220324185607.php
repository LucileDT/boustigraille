<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\Interfaces\Notifiable;
use App\Service\Migrations\NotificationService;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220324185607 extends AbstractMigration implements Notifiable
{
    private NotificationService $notificationService;

    public function setNotificationService(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    private function getMessage(): string
    {
        return '**Wow !** Boustigraille a désormais un **système de notifications**.

C\'était pas de la tarte, mais on l\'a fait et on est très content-es d\'y être parvenu-es.
Désormais, à chaque nouveauté, **vous recevrez une notification qui la détaille**.

Merci d\'utiliser Boustigraille !';
    }

    public function getDescription(): string
    {
        return 'Send latest Boustigraille update notification to everyone';
    }

    public function up(Schema $schema): void
    {
        $this->notificationService->sendBoustigrailleUpdateNotificationToEveryone($this->getMessage());
    }

    public function down(Schema $schema): void
    {
        $this->notificationService->removeBoustigrailleUpdateNotification($this->getMessage());
    }
}
