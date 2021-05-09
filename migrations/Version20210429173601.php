<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210429173601 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add user\'s nutritional goal';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE "user" ADD proteins DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD carbohydrates DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD fat DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD energy DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP proteins');
        $this->addSql('ALTER TABLE "user" DROP carbohydrates');
        $this->addSql('ALTER TABLE "user" DROP fat');
        $this->addSql('ALTER TABLE "user" DROP energy');
    }
}
