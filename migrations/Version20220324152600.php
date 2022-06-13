<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220324152600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add notifications';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE notification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE notification_receipt_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE notification (id INT NOT NULL, message TEXT NOT NULL, date_sent DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE notification_receipt (id INT NOT NULL, recipient_id INT NOT NULL, notification_id INT NOT NULL, date_read DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_46873CF2E92F8F78 ON notification_receipt (recipient_id)');
        $this->addSql('CREATE INDEX IDX_46873CF2EF1A9D84 ON notification_receipt (notification_id)');
        $this->addSql('ALTER TABLE notification_receipt ADD CONSTRAINT FK_46873CF2E92F8F78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification_receipt ADD CONSTRAINT FK_46873CF2EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE notification_receipt DROP CONSTRAINT FK_46873CF2EF1A9D84');
        $this->addSql('DROP SEQUENCE notification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE notification_receipt_id_seq CASCADE');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_receipt');
    }
}
