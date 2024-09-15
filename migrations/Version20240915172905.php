<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240915172905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Insert default follow types';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('INSERT INTO follow_type (id, code, label, description)
            VALUES (
                nextval(\'follow_type_id_seq\'),
                \'username_on_recipe\',
                \'Accès au pseudonyme dans les recettes\',
                \'Une autre personne inscrite sur votre instance Boustigraille vous propose de voir son pseudo sur les recettes qu\'\'elle a écrite.\'
            )'
        );

        $this->addSql('INSERT INTO follow_type (id, code, label, description)
            VALUES (
                nextval(\'follow_type_id_seq\'),
                \'meal_list\',
                \'Accès aux listes de repas\',
                \'Une autre personne inscrite sur votre instance Boustigraille vous propose d\'\'accéder à ses listes de repas.\'
            )'
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM follow_type WHERE code = \'username_on_recipe\' OR code = \'meal_list\'');
    }
}
