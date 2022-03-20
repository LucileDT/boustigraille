<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\Interfaces\ContentAuthorable;
use App\Service\Migrations\ContentAuthorService;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220320131613 extends AbstractMigration implements ContentAuthorable
{
    protected ContentAuthorService $authorService;

    public function getDescription(): string
    {
        return 'Fill recipe author and make it non nullable';
    }

    public function up(Schema $schema): void
    {
        $this->authorService->updateRecipesAuthor();
        $this->addSql('ALTER TABLE recipe ALTER COLUMN author_id SET NOT NULL');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137F675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DA88B137F675F31B ON recipe (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe DROP CONSTRAINT FK_DA88B137F675F31B');
        $this->addSql('DROP INDEX IDX_DA88B137F675F31B');
    }

    public function setAuthorService(ContentAuthorService $authorService)
    {
        $this->authorService = $authorService;
    }
}
