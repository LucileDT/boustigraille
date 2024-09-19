<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240912103522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change recipe tags index names and order';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe_tag DROP CONSTRAINT recipe_tag_pkey');
        $this->addSql('ALTER TABLE recipe_tag ADD PRIMARY KEY (recipe_id, tag_id)');
        $this->addSql('ALTER INDEX idx_33c9f81b59d8a214 RENAME TO IDX_72DED3CF59D8A214');
        $this->addSql('ALTER INDEX idx_33c9f81bbad26311 RENAME TO IDX_72DED3CFBAD26311');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX recipe_tag_pkey');
        $this->addSql('ALTER TABLE recipe_tag ADD PRIMARY KEY (tag_id, recipe_id)');
        $this->addSql('ALTER INDEX idx_72ded3cf59d8a214 RENAME TO idx_33c9f81b59d8a214');
        $this->addSql('ALTER INDEX idx_72ded3cfbad26311 RENAME TO idx_33c9f81bbad26311');
    }
}
