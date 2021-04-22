<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create responsibilities and users
 */
final class Version20210422232022 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Create and fill responsibilities and create users table.';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE SEQUENCE responsibility_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE responsibility (id INT NOT NULL, code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description VARCHAR(1000) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_694E8A0877153098 ON responsibility (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_694E8A08EA750E8 ON responsibility (label)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON "user" (username)');
        $this->addSql('CREATE TABLE users_responsibilities (user_id INT NOT NULL, responsibility_id INT NOT NULL, PRIMARY KEY(user_id, responsibility_id))');
        $this->addSql('CREATE INDEX IDX_59AC8836A76ED395 ON users_responsibilities (user_id)');
        $this->addSql('CREATE INDEX IDX_59AC8836385A88B7 ON users_responsibilities (responsibility_id)');
        $this->addSql('ALTER TABLE users_responsibilities ADD CONSTRAINT FK_59AC8836A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users_responsibilities ADD CONSTRAINT FK_59AC8836385A88B7 FOREIGN KEY (responsibility_id) REFERENCES responsibility (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Insert default application roles
        $this->addSql(
                "INSERT INTO responsibility VALUES "
                . "(1,'ROLE_ADMIN','Administrateurice de la base de données','Permet à l''utilisateurice de créer, éditer et supprimer les comptes utilisateurs de la base de données, ainsi que de gérer les repas et ingrédients.');"
        );
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE users_responsibilities DROP CONSTRAINT FK_59AC8836385A88B7');
        $this->addSql('ALTER TABLE users_responsibilities DROP CONSTRAINT FK_59AC8836A76ED395');
        $this->addSql('DROP SEQUENCE responsibility_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_id_seq CASCADE');
        $this->addSql('DROP TABLE responsibility');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE users_responsibilities');
    }
}
