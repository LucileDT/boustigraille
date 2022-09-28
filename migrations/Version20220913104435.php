<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220913104435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change start and end dates of meal list to date time instead of simple dates';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meal_list ALTER start_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE meal_list ALTER start_date DROP DEFAULT');
        $this->addSql('ALTER TABLE meal_list ALTER end_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE meal_list ALTER end_date DROP DEFAULT');
        $this->addSql('UPDATE meal_list SET start_date = start_date + interval \'1h\' * 16 WHERE meal_list.is_starting_at_lunch = false');
        $this->addSql('UPDATE meal_list SET end_date = end_date + interval \'1h\' * 16 WHERE meal_list.is_ending_at_lunch = true');
        $this->addSql('UPDATE meal_list SET end_date = end_date + interval \'1h\' * 23 WHERE meal_list.is_ending_at_lunch = false');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE meal_list ALTER start_date TYPE DATE');
        $this->addSql('ALTER TABLE meal_list ALTER start_date DROP DEFAULT');
        $this->addSql('ALTER TABLE meal_list ALTER end_date TYPE DATE');
        $this->addSql('ALTER TABLE meal_list ALTER end_date DROP DEFAULT');
    }
}
