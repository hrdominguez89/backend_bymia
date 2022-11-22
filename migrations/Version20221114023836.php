<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114023836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE mia_product_specification_id_seq CASCADE');
        $this->addSql('ALTER TABLE mia_product_specification DROP CONSTRAINT fk_ad7a82e74584665a');
        $this->addSql('ALTER TABLE mia_product_specification DROP CONSTRAINT fk_ad7a82e7908e2ffe');
        $this->addSql('DROP TABLE mia_product_specification');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE mia_product_specification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mia_product_specification (id BIGINT NOT NULL, product_id BIGINT NOT NULL, specification_id BIGINT NOT NULL, value VARCHAR(255) NOT NULL, custom_fields_type VARCHAR(255) NOT NULL, custom_fields_value VARCHAR(255) NOT NULL, create_variation BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_ad7a82e7908e2ffe ON mia_product_specification (specification_id)');
        $this->addSql('CREATE INDEX idx_ad7a82e74584665a ON mia_product_specification (product_id)');
        $this->addSql('ALTER TABLE mia_product_specification ADD CONSTRAINT fk_ad7a82e74584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_specification ADD CONSTRAINT fk_ad7a82e7908e2ffe FOREIGN KEY (specification_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
