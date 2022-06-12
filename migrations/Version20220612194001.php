<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220612194001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Save when a user can access another user\'s username on recipes';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE follow_username_on_recipe (id INT NOT NULL, follower_id INT NOT NULL, followed_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4ACE6DBAAC24F853 ON follow_username_on_recipe (follower_id)');
        $this->addSql('CREATE INDEX IDX_4ACE6DBAD956F010 ON follow_username_on_recipe (followed_id)');
        $this->addSql('ALTER TABLE follow_username_on_recipe ADD CONSTRAINT FK_4ACE6DBAAC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_username_on_recipe ADD CONSTRAINT FK_4ACE6DBAD956F010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_username_on_recipe ADD CONSTRAINT FK_4ACE6DBABF396750 FOREIGN KEY (id) REFERENCES action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('INSERT INTO notification_category (id, code, name, description) 
                           SELECT COALESCE(MAX(id), 0) + 1, 3, \'Accès au pseudonyme dans les recettes\', \'Une autre personne inscrite sur votre instance Boustigraille vous propose de voir son pseudo sur les recettes qu\'\'elle a écrites.\' FROM notification_category');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE follow_username_on_recipe');
        $this->addSql('DELETE FROM notification_category WHERE code = 3');
    }
}
