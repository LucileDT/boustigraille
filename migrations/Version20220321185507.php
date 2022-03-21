<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220321185507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add meal list author field';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meal_list ADD author_id INT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meal_list DROP author_id');
    }
}
