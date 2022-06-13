<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220321210711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Allow user to show their username on meal list they have written';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD do_show_username_on_meal_list BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP do_show_username_on_meal_list');
    }
}
