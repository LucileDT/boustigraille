<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220321154944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Save if user agree to show their username on recipes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD do_show_username_on_recipe BOOLEAN DEFAULT false NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" DROP do_show_username_on_recipe');
    }
}
