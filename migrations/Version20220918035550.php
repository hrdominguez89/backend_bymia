<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220918035550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_cover_image ADD pre_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_cover_image ADD number_order SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_cover_image DROP image_lg');
        $this->addSql('ALTER TABLE mia_cover_image DROP image_sm');
        $this->addSql('ALTER TABLE mia_cover_image RENAME COLUMN main TO visible');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_cover_image ADD image_lg VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_cover_image ADD image_sm VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_cover_image DROP pre_title');
        $this->addSql('ALTER TABLE mia_cover_image DROP number_order');
        $this->addSql('ALTER TABLE mia_cover_image RENAME COLUMN visible TO main');
    }
}
