<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220324185305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add category to notification';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification ADD category_id INT NOT NULL DEFAULT 1');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA12469DE2 FOREIGN KEY (category_id) REFERENCES notification_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_BF5476CA12469DE2 ON notification (category_id)');
        $this->addSql('ALTER TABLE notification ALTER category_id DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA12469DE2');
        $this->addSql('DROP INDEX IDX_BF5476CA12469DE2');
        $this->addSql('ALTER TABLE notification DROP category_id');
    }
}
