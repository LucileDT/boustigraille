<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230122230729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Link ingredients to a Store';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient ADD store_id INT');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870B092A811 FOREIGN KEY (store_id) REFERENCES store (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_6BAF7870B092A811 ON ingredient (store_id)');
        $this->addSql('UPDATE ingredient SET store_id = (SELECT id FROM store WHERE ingredient.shop = store.label)');
        $this->addSql('ALTER TABLE ingredient DROP shop');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient ADD shop VARCHAR(255) DEFAULT NULL');
        $this->addSql('UPDATE ingredient SET shop = (SELECT label FROM store WHERE ingredient.store_id = store.id)');
        $this->addSql('ALTER TABLE ingredient DROP CONSTRAINT FK_6BAF7870B092A811');
        $this->addSql('DROP INDEX IDX_6BAF7870B092A811');
        $this->addSql('ALTER TABLE ingredient DROP store_id');
    }
}
