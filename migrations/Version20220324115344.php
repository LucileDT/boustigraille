<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220324115344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Save new notification categories';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO notification_category (id, code, name, description) 
                           SELECT COALESCE(MAX(id), 0) + 1, 1, \'Mise à jour Boustigraille\', \'Nouveautés disponibles dans Boustigraille qui ont été installées sur votre instance.\' FROM notification_category');
        $this->addSql('INSERT INTO notification_category (id, code, name, description) 
                           SELECT COALESCE(MAX(id), 0) + 1, 2, \'Accès aux listes de repas\', \'Une autre personne inscrite sur votre instance Boustigraille vous propose d\'\'accéder à ses listes de repas.\' FROM notification_category');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM notification_category where code = 1');
        $this->addSql('DELETE FROM notification_category where code = 2');
    }
}
