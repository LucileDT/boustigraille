<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240921102138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rename followRequest to followProposition to better match the use cases.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE follow_request_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE follow_proposition_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE follow_proposition (id INT NOT NULL, type_id INT NOT NULL, followed_id INT NOT NULL, follower_id INT NOT NULL, proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, processed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D089BCAC54C8C93 ON follow_proposition (type_id)');
        $this->addSql('CREATE INDEX IDX_5D089BCAD956F010 ON follow_proposition (followed_id)');
        $this->addSql('CREATE INDEX IDX_5D089BCAAC24F853 ON follow_proposition (follower_id)');
        $this->addSql('COMMENT ON COLUMN follow_proposition.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_proposition.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_proposition.refused_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_proposition.processed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE follow_proposition ADD CONSTRAINT FK_5D089BCAC54C8C93 FOREIGN KEY (type_id) REFERENCES follow_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_proposition ADD CONSTRAINT FK_5D089BCAD956F010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_proposition ADD CONSTRAINT FK_5D089BCAAC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_request DROP CONSTRAINT fk_6562d72fc54c8c93');
        $this->addSql('ALTER TABLE follow_request DROP CONSTRAINT fk_6562d72fac24f853');
        $this->addSql('ALTER TABLE follow_request DROP CONSTRAINT fk_6562d72fd956f010');
        $this->addSql('DROP TABLE follow_request');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE follow_proposition_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE follow_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE follow_request (id INT NOT NULL, type_id INT NOT NULL, follower_id INT NOT NULL, followed_id INT NOT NULL, proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, processed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_6562d72fd956f010 ON follow_request (followed_id)');
        $this->addSql('CREATE INDEX idx_6562d72fac24f853 ON follow_request (follower_id)');
        $this->addSql('CREATE INDEX idx_6562d72fc54c8c93 ON follow_request (type_id)');
        $this->addSql('COMMENT ON COLUMN follow_request.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_request.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_request.refused_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_request.processed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE follow_request ADD CONSTRAINT fk_6562d72fc54c8c93 FOREIGN KEY (type_id) REFERENCES follow_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_request ADD CONSTRAINT fk_6562d72fac24f853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_request ADD CONSTRAINT fk_6562d72fd956f010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_proposition DROP CONSTRAINT FK_5D089BCAC54C8C93');
        $this->addSql('ALTER TABLE follow_proposition DROP CONSTRAINT FK_5D089BCAD956F010');
        $this->addSql('ALTER TABLE follow_proposition DROP CONSTRAINT FK_5D089BCAAC24F853');
        $this->addSql('DROP TABLE follow_proposition');
    }
}
