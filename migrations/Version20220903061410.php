<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903061410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea3744f5d008');
        $this->addSql('DROP INDEX idx_540dea3744f5d008');
        $this->addSql('ALTER TABLE mia_product ADD cod VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD part_number VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD onhand INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD commited INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD incomming INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD available INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD id_3pl INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD short_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD cost DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD created_at_3pl TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD updated_at_3pl TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP brand_id');
        $this->addSql('ALTER TABLE mia_product DROP parent_id');
        $this->addSql('ALTER TABLE mia_product DROP badges');
        $this->addSql('ALTER TABLE mia_product DROP availability');
        $this->addSql('ALTER TABLE mia_product DROP name');
        $this->addSql('ALTER TABLE mia_product DROP image');
        $this->addSql('ALTER TABLE mia_product DROP description');
        $this->addSql('ALTER TABLE mia_product DROP stock');
        $this->addSql('ALTER TABLE mia_product DROP url');
        $this->addSql('ALTER TABLE mia_product DROP price');
        $this->addSql('ALTER TABLE mia_product DROP offer_price');
        $this->addSql('ALTER TABLE mia_product DROP offer_start_date');
        $this->addSql('ALTER TABLE mia_product DROP offer_end_date');
        $this->addSql('ALTER TABLE mia_product DROP html_description');
        $this->addSql('ALTER TABLE mia_product DROP color');
        $this->addSql('ALTER TABLE mia_product DROP length');
        $this->addSql('ALTER TABLE mia_product DROP dimensions');
        $this->addSql('ALTER TABLE mia_product DROP date');
        $this->addSql('ALTER TABLE mia_product DROP featured');
        $this->addSql('ALTER TABLE mia_product DROP sales');
        $this->addSql('ALTER TABLE mia_product DROP reviews');
        $this->addSql('ALTER TABLE mia_product DROP rating');
        $this->addSql('ALTER TABLE mia_product DROP new_image');
        $this->addSql('ALTER TABLE mia_product ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_product_image DROP CONSTRAINT fk_adc15e194584665a');
        $this->addSql('DROP INDEX idx_adc15e194584665a');
        $this->addSql('ALTER TABLE mia_product_image DROP product_id');
        $this->addSql('ALTER TABLE mia_product_image DROP new');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ADD brand_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD parent_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD badges VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD availability VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD offer_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD offer_start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD offer_end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD html_description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD length DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD dimensions VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD featured BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD sales DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD reviews DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD rating DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD new_image TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP cod');
        $this->addSql('ALTER TABLE mia_product DROP part_number');
        $this->addSql('ALTER TABLE mia_product DROP onhand');
        $this->addSql('ALTER TABLE mia_product DROP commited');
        $this->addSql('ALTER TABLE mia_product DROP incomming');
        $this->addSql('ALTER TABLE mia_product DROP available');
        $this->addSql('ALTER TABLE mia_product DROP id_3pl');
        $this->addSql('ALTER TABLE mia_product DROP short_name');
        $this->addSql('ALTER TABLE mia_product DROP long_name');
        $this->addSql('ALTER TABLE mia_product DROP created_at');
        $this->addSql('ALTER TABLE mia_product DROP created_at_3pl');
        $this->addSql('ALTER TABLE mia_product DROP updated_at_3pl');
        $this->addSql('ALTER TABLE mia_product ALTER slug TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN long_description TO image');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN cost TO stock');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea3744f5d008 FOREIGN KEY (brand_id) REFERENCES mia_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea3744f5d008 ON mia_product (brand_id)');
        $this->addSql('ALTER TABLE mia_product_image ADD product_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product_image ADD new BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product_image ADD CONSTRAINT fk_adc15e194584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_adc15e194584665a ON mia_product_image (product_id)');
    }
}
