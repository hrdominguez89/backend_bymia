<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014113645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea375dc6fe57');
        $this->addSql('DROP INDEX idx_540dea375dc6fe57');
        $this->addSql('ALTER TABLE mia_product ADD title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP subcategory_id');
        $this->addSql('ALTER TABLE mia_product DROP short_description');
        $this->addSql('ALTER TABLE mia_product DROP short_name');
        $this->addSql('ALTER TABLE mia_product DROP long_name');
        $this->addSql('ALTER TABLE mia_product DROP long_description');
        $this->addSql('ALTER TABLE mia_product ALTER part_number TYPE VARCHAR(15)');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN cod TO upc');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ADD subcategory_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER part_number TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN description TO short_description');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN upc TO cod');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN title TO short_name');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea375dc6fe57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea375dc6fe57 ON mia_product (subcategory_id)');
    }
}
