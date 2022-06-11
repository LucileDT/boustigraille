<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220422213002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create action entity and link follow meal list action to it';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE follow_meal_list_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE action (id INT NOT NULL, proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN action.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN action.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN action.refused_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('INSERT INTO action (id, proposed_at, accepted_at, refused_at, type) SELECT id, proposed_at, accepted_at, refused_at, \'follow_meal_list\' FROM follow_meal_list');
        $this->addSql('ALTER TABLE follow_meal_list DROP proposed_at');
        $this->addSql('ALTER TABLE follow_meal_list DROP accepted_at');
        $this->addSql('ALTER TABLE follow_meal_list DROP refused_at');
        $this->addSql('ALTER TABLE follow_meal_list ADD CONSTRAINT FK_DF630267BF396750 FOREIGN KEY (id) REFERENCES action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476cacd544a2a');
        $this->addSql('DROP INDEX uniq_bf5476cacd544a2a');
        $this->addSql('ALTER TABLE notification RENAME COLUMN follow_meal_list_id TO action_id');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9D32F035 FOREIGN KEY (action_id) REFERENCES action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CA9D32F035 ON notification (action_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE follow_meal_list DROP CONSTRAINT FK_DF630267BF396750');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9D32F035');
        $this->addSql('DROP SEQUENCE action_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE follow_meal_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE follow_meal_list ADD proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE follow_meal_list ADD accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE follow_meal_list ADD refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN follow_meal_list.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_meal_list.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_meal_list.refused_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('UPDATE follow_meal_list SET proposed_at = action.proposed_at FROM follow_meal_list fml, action WHERE fml.id = action.id');
        $this->addSql('UPDATE follow_meal_list SET accepted_at = action.accepted_at FROM follow_meal_list fml, action WHERE fml.id = action.id');
        $this->addSql('UPDATE follow_meal_list SET refused_at = action.refused_at FROM follow_meal_list fml, action WHERE fml.id = action.id');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP INDEX UNIQ_BF5476CA9D32F035');
        $this->addSql('ALTER TABLE notification RENAME COLUMN action_id TO follow_meal_list_id');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476cacd544a2a FOREIGN KEY (follow_meal_list_id) REFERENCES follow_meal_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_bf5476cacd544a2a ON notification (follow_meal_list_id)');
    }
}
