<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221104030434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_cover_image RENAME COLUMN description TO subtitle');
        $this->addSql('ALTER TABLE mia_cover_image RENAME COLUMN pre_title TO volanta');
        $this->addSql('ALTER TABLE mia_product_image ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product_image ADD CONSTRAINT FK_ADC15E194584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_ADC15E194584665A ON mia_product_image (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product_image DROP CONSTRAINT FK_ADC15E194584665A');
        $this->addSql('DROP INDEX IDX_ADC15E194584665A');
        $this->addSql('ALTER TABLE mia_product_image DROP product_id');
        $this->addSql('ALTER TABLE mia_cover_image RENAME COLUMN subtitle TO description');
        $this->addSql('ALTER TABLE mia_cover_image RENAME COLUMN volanta TO pre_title');
    }
}
