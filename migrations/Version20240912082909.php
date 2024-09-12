<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240912082909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rework of the notifications and follow requests database schema.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE notification_receipt_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE action_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE follow_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE follow_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_received_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_sent_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE follow_request (id INT NOT NULL, type_id INT NOT NULL, follower_id INT NOT NULL, followed_id INT NOT NULL, proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6562D72FC54C8C93 ON follow_request (type_id)');
        $this->addSql('CREATE INDEX IDX_6562D72FAC24F853 ON follow_request (follower_id)');
        $this->addSql('CREATE INDEX IDX_6562D72FD956F010 ON follow_request (followed_id)');
        $this->addSql('COMMENT ON COLUMN follow_request.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_request.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN follow_request.refused_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE follow_type (id INT NOT NULL, code VARCHAR(255) NOT NULL, label VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE notification_history (id INT NOT NULL, subject VARCHAR(255) NOT NULL, content TEXT DEFAULT NULL, emoji VARCHAR(1) DEFAULT NULL, importance INT NOT NULL, channels JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE notification_received (id INT NOT NULL, recipient_id INT DEFAULT NULL, notification_history_id INT NOT NULL, read_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C7C0162E92F8F78 ON notification_received (recipient_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C7C0162E5772170 ON notification_received (notification_history_id)');
        $this->addSql('COMMENT ON COLUMN notification_received.read_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification_sent (id INT NOT NULL, sender_id INT DEFAULT NULL, notification_history_id INT NOT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2F86193F624B39D ON notification_sent (sender_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2F86193E5772170 ON notification_sent (notification_history_id)');
        $this->addSql('COMMENT ON COLUMN notification_sent.sent_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE follow_request ADD CONSTRAINT FK_6562D72FC54C8C93 FOREIGN KEY (type_id) REFERENCES follow_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_request ADD CONSTRAINT FK_6562D72FAC24F853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_request ADD CONSTRAINT FK_6562D72FD956F010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_received ADD CONSTRAINT FK_9C7C0162E92F8F78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_received ADD CONSTRAINT FK_9C7C0162E5772170 FOREIGN KEY (notification_history_id) REFERENCES notification_history (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_sent ADD CONSTRAINT FK_2F86193F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_sent ADD CONSTRAINT FK_2F86193E5772170 FOREIGN KEY (notification_history_id) REFERENCES notification_history (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476caf624b39d');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476ca12469de2');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT fk_bf5476ca9d32f035');
        $this->addSql('ALTER TABLE follow_meal_list DROP CONSTRAINT fk_df630267ac24f853');
        $this->addSql('ALTER TABLE follow_meal_list DROP CONSTRAINT fk_df630267d956f010');
        $this->addSql('ALTER TABLE follow_meal_list DROP CONSTRAINT fk_df630267bf396750');
        $this->addSql('ALTER TABLE notification_receipt DROP CONSTRAINT fk_46873cf2e92f8f78');
        $this->addSql('ALTER TABLE notification_receipt DROP CONSTRAINT fk_46873cf2ef1a9d84');
        $this->addSql('ALTER TABLE follow_username_on_recipe DROP CONSTRAINT fk_4ace6dbaac24f853');
        $this->addSql('ALTER TABLE follow_username_on_recipe DROP CONSTRAINT fk_4ace6dbad956f010');
        $this->addSql('ALTER TABLE follow_username_on_recipe DROP CONSTRAINT fk_4ace6dbabf396750');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE follow_meal_list');
        $this->addSql('DROP TABLE notification_receipt');
        $this->addSql('DROP TABLE follow_username_on_recipe');
        $this->addSql('DROP TABLE notification_category');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE follow_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE follow_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_history_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_received_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_sent_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE notification_receipt_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE action (id INT NOT NULL, proposed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, accepted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, refused_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN action.proposed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN action.accepted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN action.refused_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, sender_id INT DEFAULT NULL, category_id INT NOT NULL, action_id INT DEFAULT NULL, message TEXT NOT NULL, date_sent DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_bf5476ca9d32f035 ON notification (action_id)');
        $this->addSql('CREATE INDEX idx_bf5476ca12469de2 ON notification (category_id)');
        $this->addSql('CREATE INDEX idx_bf5476caf624b39d ON notification (sender_id)');
        $this->addSql('CREATE TABLE follow_meal_list (id INT NOT NULL, follower_id INT NOT NULL, followed_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_df630267d956f010 ON follow_meal_list (followed_id)');
        $this->addSql('CREATE INDEX idx_df630267ac24f853 ON follow_meal_list (follower_id)');
        $this->addSql('CREATE TABLE notification_receipt (id INT NOT NULL, recipient_id INT NOT NULL, notification_id INT NOT NULL, date_read DATE DEFAULT NULL, processed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_46873cf2ef1a9d84 ON notification_receipt (notification_id)');
        $this->addSql('CREATE INDEX idx_46873cf2e92f8f78 ON notification_receipt (recipient_id)');
        $this->addSql('COMMENT ON COLUMN notification_receipt.processed_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE follow_username_on_recipe (id INT NOT NULL, follower_id INT NOT NULL, followed_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_4ace6dbad956f010 ON follow_username_on_recipe (followed_id)');
        $this->addSql('CREATE INDEX idx_4ace6dbaac24f853 ON follow_username_on_recipe (follower_id)');
        $this->addSql('CREATE TABLE notification_category (id INT NOT NULL, code INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476caf624b39d FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476ca12469de2 FOREIGN KEY (category_id) REFERENCES notification_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT fk_bf5476ca9d32f035 FOREIGN KEY (action_id) REFERENCES action (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_meal_list ADD CONSTRAINT fk_df630267ac24f853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_meal_list ADD CONSTRAINT fk_df630267d956f010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_meal_list ADD CONSTRAINT fk_df630267bf396750 FOREIGN KEY (id) REFERENCES action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_receipt ADD CONSTRAINT fk_46873cf2e92f8f78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_receipt ADD CONSTRAINT fk_46873cf2ef1a9d84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_username_on_recipe ADD CONSTRAINT fk_4ace6dbaac24f853 FOREIGN KEY (follower_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_username_on_recipe ADD CONSTRAINT fk_4ace6dbad956f010 FOREIGN KEY (followed_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_username_on_recipe ADD CONSTRAINT fk_4ace6dbabf396750 FOREIGN KEY (id) REFERENCES action (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE follow_request DROP CONSTRAINT FK_6562D72FC54C8C93');
        $this->addSql('ALTER TABLE follow_request DROP CONSTRAINT FK_6562D72FAC24F853');
        $this->addSql('ALTER TABLE follow_request DROP CONSTRAINT FK_6562D72FD956F010');
        $this->addSql('ALTER TABLE notification_received DROP CONSTRAINT FK_9C7C0162E92F8F78');
        $this->addSql('ALTER TABLE notification_received DROP CONSTRAINT FK_9C7C0162E5772170');
        $this->addSql('ALTER TABLE notification_sent DROP CONSTRAINT FK_2F86193F624B39D');
        $this->addSql('ALTER TABLE notification_sent DROP CONSTRAINT FK_2F86193E5772170');
        $this->addSql('DROP TABLE follow_request');
        $this->addSql('DROP TABLE follow_type');
        $this->addSql('DROP TABLE notification_history');
        $this->addSql('DROP TABLE notification_received');
        $this->addSql('DROP TABLE notification_sent');
    }
}
