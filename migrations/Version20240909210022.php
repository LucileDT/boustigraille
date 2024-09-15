<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240909210022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add recipe tags';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_389B783EA750E8 ON tag (label)');
        $this->addSql('CREATE TABLE recipe_tag (tag_id INT NOT NULL, recipe_id INT NOT NULL, PRIMARY KEY(tag_id, recipe_id))');
        $this->addSql('CREATE INDEX IDX_33C9F81BBAD26311 ON recipe_tag (tag_id)');
        $this->addSql('CREATE INDEX IDX_33C9F81B59D8A214 ON recipe_tag (recipe_id)');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_33C9F81BBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipe_tag ADD CONSTRAINT FK_33C9F81B59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient ALTER has_stock_check_needed_before_buying DROP DEFAULT');
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Facile\')');
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Difficile\')');
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Rapide\')');
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Long\')');
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Végé\')');
        $this->addSql('INSERT INTO tag VALUES (nextval(\'tag_id_seq\'), \'Vegan\')');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('ALTER TABLE recipe_tag DROP CONSTRAINT FK_33C9F81BBAD26311');
        $this->addSql('ALTER TABLE recipe_tag DROP CONSTRAINT FK_33C9F81B59D8A214');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE recipe_tag');
        $this->addSql('ALTER TABLE ingredient ALTER has_stock_check_needed_before_buying SET DEFAULT false');
    }
}
