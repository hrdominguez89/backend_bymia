<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014122215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_subcategory (product_id BIGINT NOT NULL, subcategory_id BIGINT NOT NULL, PRIMARY KEY(product_id, subcategory_id))');
        $this->addSql('CREATE INDEX IDX_A1F33A574584665A ON product_subcategory (product_id)');
        $this->addSql('CREATE INDEX IDX_A1F33A575DC6FE57 ON product_subcategory (subcategory_id)');
        $this->addSql('ALTER TABLE product_subcategory ADD CONSTRAINT FK_A1F33A574584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_subcategory ADD CONSTRAINT FK_A1F33A575DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD screen_resolution VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD cpu VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD gpu VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD ram VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD memory VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD screen_size VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD op_sys VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD model VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP created_at_3pl');
        $this->addSql('ALTER TABLE mia_product DROP updated_at_3pl');
        $this->addSql('ALTER TABLE mia_product ALTER weight TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_subcategory DROP CONSTRAINT FK_A1F33A574584665A');
        $this->addSql('ALTER TABLE product_subcategory DROP CONSTRAINT FK_A1F33A575DC6FE57');
        $this->addSql('DROP TABLE product_subcategory');
        $this->addSql('ALTER TABLE mia_product ADD updated_at_3pl TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP color');
        $this->addSql('ALTER TABLE mia_product DROP screen_resolution');
        $this->addSql('ALTER TABLE mia_product DROP cpu');
        $this->addSql('ALTER TABLE mia_product DROP gpu');
        $this->addSql('ALTER TABLE mia_product DROP ram');
        $this->addSql('ALTER TABLE mia_product DROP memory');
        $this->addSql('ALTER TABLE mia_product DROP screen_size');
        $this->addSql('ALTER TABLE mia_product DROP op_sys');
        $this->addSql('ALTER TABLE mia_product DROP model');
        $this->addSql('ALTER TABLE mia_product ALTER weight TYPE DOUBLE PRECISION');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN updated_at TO created_at_3pl');
    }
}
