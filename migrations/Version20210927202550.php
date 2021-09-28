<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20210927202550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE 
            flows (
                id INT AUTO_INCREMENT NOT NULL,
                 station_id INT NOT NULL,
                 river_id INT NOT NULL,
                 datetime DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                 level NUMERIC(10, 3) DEFAULT NULL,
                 flow NUMERIC(10, 3) NOT NULL,
                 status VARCHAR(255) DEFAULT NULL, 
                 created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                 UNIQUE INDEX flow_record_idx (datetime, station_id, river_id), 
                 PRIMARY KEY(id),
                 INDEX (datetime)
             ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
    }
}
