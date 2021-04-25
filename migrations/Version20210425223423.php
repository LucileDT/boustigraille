<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210425223423 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Make nutritional data accept floats';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE ingredient ALTER proteins TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE ingredient ALTER proteins DROP DEFAULT');
        $this->addSql('ALTER TABLE ingredient ALTER carbohydrates TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE ingredient ALTER carbohydrates DROP DEFAULT');
        $this->addSql('ALTER TABLE ingredient ALTER fat TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE ingredient ALTER fat DROP DEFAULT');
        $this->addSql('ALTER TABLE ingredient ALTER energy TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE ingredient ALTER energy DROP DEFAULT');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE ingredient ALTER proteins TYPE INT');
        $this->addSql('ALTER TABLE ingredient ALTER proteins DROP DEFAULT');
        $this->addSql('ALTER TABLE ingredient ALTER carbohydrates TYPE INT');
        $this->addSql('ALTER TABLE ingredient ALTER carbohydrates DROP DEFAULT');
        $this->addSql('ALTER TABLE ingredient ALTER fat TYPE INT');
        $this->addSql('ALTER TABLE ingredient ALTER fat DROP DEFAULT');
        $this->addSql('ALTER TABLE ingredient ALTER energy TYPE INT');
        $this->addSql('ALTER TABLE ingredient ALTER energy DROP DEFAULT');
    }
}
