<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240921110624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add tags to ingredients';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE ingredient_tag (ingredient_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(ingredient_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_8CC02FFB933FE08C ON ingredient_tag (ingredient_id)');
        $this->addSql('CREATE INDEX IDX_8CC02FFBBAD26311 ON ingredient_tag (tag_id)');
        $this->addSql('ALTER TABLE ingredient_tag ADD CONSTRAINT FK_8CC02FFB933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ingredient_tag ADD CONSTRAINT FK_8CC02FFBBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient_tag DROP CONSTRAINT FK_8CC02FFB933FE08C');
        $this->addSql('ALTER TABLE ingredient_tag DROP CONSTRAINT FK_8CC02FFBBAD26311');
        $this->addSql('DROP TABLE ingredient_tag');
    }
}
