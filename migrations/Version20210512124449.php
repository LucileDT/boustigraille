<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210512124449 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Allow saving of favorite recipes by a user';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE user_recipe (user_id INT NOT NULL, recipe_id INT NOT NULL, PRIMARY KEY(user_id, recipe_id))');
        $this->addSql('CREATE INDEX IDX_BFDAAA0AA76ED395 ON user_recipe (user_id)');
        $this->addSql('CREATE INDEX IDX_BFDAAA0A59D8A214 ON user_recipe (recipe_id)');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_recipe ADD CONSTRAINT FK_BFDAAA0A59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE user_recipe');
    }
}
