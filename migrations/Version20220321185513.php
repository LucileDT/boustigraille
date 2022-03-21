<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migrations\Interfaces\ContentAuthorable;
use App\Service\Migrations\ContentAuthorService;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220321185513 extends AbstractMigration implements ContentAuthorable
{
    protected ContentAuthorService $authorService;

    public function getDescription(): string
    {
        return 'Fill meal list author and make it non nullable';
    }

    public function up(Schema $schema): void
    {
        $this->authorService->updateMealListsAuthor();
        $this->addSql('ALTER TABLE meal_list ALTER COLUMN author_id SET NOT NULL');
        $this->addSql('ALTER TABLE meal_list ADD CONSTRAINT FK_5E162BECF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5E162BECF675F31B ON meal_list (author_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meal_list DROP CONSTRAINT FK_5E162BECF675F31B');
        $this->addSql('DROP INDEX IDX_5E162BECF675F31B');
    }

    public function setAuthorService(ContentAuthorService $authorService)
    {
        $this->authorService = $authorService;
    }
}
