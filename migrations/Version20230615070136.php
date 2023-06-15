<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615070136 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product_subcategories DROP CONSTRAINT fk_c62739b64584665a');
        $this->addSql('DROP INDEX idx_c62739b64584665a');
        $this->addSql('ALTER TABLE mia_product_subcategories DROP product_id');
        $this->addSql('ALTER TABLE mia_product_tag DROP CONSTRAINT fk_5ec3d0b74584665a');
        $this->addSql('DROP INDEX idx_5ec3d0b74584665a');
        $this->addSql('ALTER TABLE mia_product_tag DROP product_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product_tag ADD product_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product_tag ADD CONSTRAINT fk_5ec3d0b74584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5ec3d0b74584665a ON mia_product_tag (product_id)');
        $this->addSql('ALTER TABLE mia_product_subcategories ADD product_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product_subcategories ADD CONSTRAINT fk_c62739b64584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_c62739b64584665a ON mia_product_subcategories (product_id)');
    }
}
