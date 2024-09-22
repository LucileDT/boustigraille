<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240922203830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add a carnist tag';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Carniste\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM tag WHERE label = \'Carniste\'');
    }
}
