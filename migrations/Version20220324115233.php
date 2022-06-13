<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220324115233 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add notification categories';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE notification_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notification_category (id INT NOT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE notification_category_id_seq CASCADE');
        $this->addSql('DROP TABLE notification_category');
    }
}
