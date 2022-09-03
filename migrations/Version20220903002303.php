<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903002303 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subcategory_category (subcategory_id BIGINT NOT NULL, category_id BIGINT NOT NULL, PRIMARY KEY(subcategory_id, category_id))');
        $this->addSql('CREATE INDEX IDX_B33C6E275DC6FE57 ON subcategory_category (subcategory_id)');
        $this->addSql('CREATE INDEX IDX_B33C6E2712469DE2 ON subcategory_category (category_id)');
        $this->addSql('ALTER TABLE subcategory_category ADD CONSTRAINT FK_B33C6E275DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subcategory_category ADD CONSTRAINT FK_B33C6E2712469DE2 FOREIGN KEY (category_id) REFERENCES mia_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_category DROP path');
        $this->addSql('ALTER TABLE mia_category DROP type');
        $this->addSql('ALTER TABLE mia_category DROP active');
        $this->addSql('ALTER TABLE mia_category DROP items');
        $this->addSql('ALTER TABLE mia_sub_category DROP CONSTRAINT fk_10e26beb3397707a');
        $this->addSql('DROP INDEX idx_10e26beb3397707a');
        $this->addSql('ALTER TABLE mia_sub_category DROP categoria_id');
        $this->addSql('ALTER TABLE mia_sub_category DROP path');
        $this->addSql('ALTER TABLE mia_sub_category DROP type');
        $this->addSql('ALTER TABLE mia_sub_category DROP active');
        $this->addSql('ALTER TABLE mia_sub_category DROP items');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE subcategory_category');
        $this->addSql('ALTER TABLE mia_sub_category ADD categoria_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD type VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD items INT NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD CONSTRAINT fk_10e26beb3397707a FOREIGN KEY (categoria_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_10e26beb3397707a ON mia_sub_category (categoria_id)');
        $this->addSql('ALTER TABLE mia_category ADD path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD type VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE mia_category ADD active BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE mia_category ADD items INT NOT NULL');
    }
}
