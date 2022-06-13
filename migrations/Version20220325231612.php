<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220325231612 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add meal list following';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE follow_meal_list_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE follow_meal_list (id INT NOT NULL, follower_id INT NOT NULL, followed_id INT NOT NULL, proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DF630267AC24F853 ON follow_meal_list (follower_id)');
        $this->addSql('CREATE INDEX IDX_DF630267D956F010 ON follow_meal_list (followed_id)');
        $this->addSql('COMMENT ON COLUMN follow_meal_list.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_meal_list.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE follow_meal_list ADD CONSTRAINT FK_DF630267AC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_meal_list ADD CONSTRAINT FK_DF630267D956F010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('ALTER TABLE notification_receipt ADD processed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN notification_receipt.processed_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE follow_meal_list ADD refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('COMMENT ON COLUMN follow_meal_list.refused_at IS \'(DC2Type:datetime_immutable)\'');

        $this->addSql('ALTER TABLE notification ADD follow_meal_list_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CACD544A2A FOREIGN KEY (follow_meal_list_id) REFERENCES follow_meal_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BF5476CACD544A2A ON notification (follow_meal_list_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CACD544A2A');
        $this->addSql('DROP INDEX UNIQ_BF5476CACD544A2A');
        $this->addSql('ALTER TABLE notification DROP follow_meal_list_id');

        $this->addSql('ALTER TABLE follow_meal_list DROP refused_at');

        $this->addSql('ALTER TABLE notification_receipt DROP processed_at');

        $this->addSql('DROP SEQUENCE follow_meal_list_id_seq CASCADE');
        $this->addSql('DROP TABLE follow_meal_list');
    }
}
