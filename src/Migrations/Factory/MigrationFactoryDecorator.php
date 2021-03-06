<?php
declare(strict_types=1);

namespace App\Migrations\Factory;

use App\Migrations\Interfaces\Notifiable;
use App\Service\Migrations\ContentAuthorService;
use App\Service\Migrations\NotificationService;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\MigrationFactory;
use App\Migrations\Interfaces\ContentAuthorable;

class MigrationFactoryDecorator implements MigrationFactory
{
    private $migrationFactory;
    private $authorService;

    public function __construct(
        MigrationFactory $migrationFactory,
        ContentAuthorService $authorService,
        NotificationService $notificationService
    )
    {
        $this->migrationFactory = $migrationFactory;
        $this->authorService = $authorService;
        $this->notificationService = $notificationService;
    }

    public function createVersion(string $migrationClassName): AbstractMigration
    {
        $instance = $this->migrationFactory->createVersion($migrationClassName);

        if ($instance instanceof ContentAuthorable) {
            $instance->setAuthorService($this->authorService);
        }
        if ($instance instanceof Notifiable) {
            $instance->setNotificationService($this->notificationService);
        }

        return $instance;
    }
}
