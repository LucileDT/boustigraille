<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210515113905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Remove "Générique" ingredients brand';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('UPDATE ingredient SET brand = NULL WHERE brand = \'Générique\'');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('UPDATE ingredient SET brand = \'Générique\' WHERE brand = NULL');
    }
}
