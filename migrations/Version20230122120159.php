<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230122120159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add default stores to Boustigraille';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE store_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE store (id INT NOT NULL, label VARCHAR(255) NOT NULL, sort_number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF575877282B8973 ON store (sort_number)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Lidl\', 1)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Carrefour\', 2)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Casino\', 3)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Monoprix\', 4)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Aldi\', 5)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Wei Son\', 7)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Picard\', 8)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Thiriet\', 9)');
        $this->addSql('INSERT INTO store (id, label, sort_number) VALUES (nextval(\'store_id_seq\'), \'Auchan\', 10)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_FF575877282B8973');
        $this->addSql('DROP SEQUENCE store_id_seq CASCADE');
        $this->addSql('DROP TABLE store');
    }
}
