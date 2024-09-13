<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913132947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change notification_history.importance type from int to string.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification_history ALTER importance TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification_history ALTER importance TYPE INT');
    }
}
