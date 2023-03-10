<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230309183143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide_numbers ADD courier_id INT NOT NULL');
        $this->addSql('ALTER TABLE guide_numbers ADD lb DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE guide_numbers ADD height DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE guide_numbers ADD width DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE guide_numbers ADD depth DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE guide_numbers ADD service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE guide_numbers ADD service_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE guide_numbers RENAME COLUMN courier TO courier_name');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE guide_numbers DROP courier_id');
        $this->addSql('ALTER TABLE guide_numbers DROP lb');
        $this->addSql('ALTER TABLE guide_numbers DROP height');
        $this->addSql('ALTER TABLE guide_numbers DROP width');
        $this->addSql('ALTER TABLE guide_numbers DROP depth');
        $this->addSql('ALTER TABLE guide_numbers DROP service_id');
        $this->addSql('ALTER TABLE guide_numbers DROP service_name');
        $this->addSql('ALTER TABLE guide_numbers RENAME COLUMN courier_name TO courier');
    }
}
