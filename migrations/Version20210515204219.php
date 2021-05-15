<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210515204219 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Rename table users_responsibilities to user_responsibility';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE users_responsibilities RENAME TO user_responsibility;');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE user_responsibility RENAME TO users_responsibilities;');
    }
}
