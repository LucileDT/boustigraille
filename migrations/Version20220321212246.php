<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220321212246 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename a field to better match how it is used';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" RENAME COLUMN do_show_username_on_meal_list TO do_show_written_meal_list_to_others');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" RENAME COLUMN do_show_written_meal_list_to_others TO do_show_username_on_meal_list');
    }
}
