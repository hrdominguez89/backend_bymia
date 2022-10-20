<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020035619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subcategory_category DROP CONSTRAINT fk_b33c6e275dc6fe57');
        $this->addSql('ALTER TABLE subcategory_category DROP CONSTRAINT fk_b33c6e2712469de2');
        $this->addSql('DROP TABLE subcategory_category');
        $this->addSql('ALTER TABLE mia_category ADD id3pl BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category DROP api_id');
        $this->addSql('ALTER TABLE mia_category ALTER visible SET DEFAULT false');
        $this->addSql('ALTER TABLE mia_sub_category ADD id3pl BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_sub_category DROP api_id');
        $this->addSql('ALTER TABLE mia_sub_category ALTER visible SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE subcategory_category (subcategory_id BIGINT NOT NULL, category_id BIGINT NOT NULL, PRIMARY KEY(subcategory_id, category_id))');
        $this->addSql('CREATE INDEX idx_b33c6e2712469de2 ON subcategory_category (category_id)');
        $this->addSql('CREATE INDEX idx_b33c6e275dc6fe57 ON subcategory_category (subcategory_id)');
        $this->addSql('ALTER TABLE subcategory_category ADD CONSTRAINT fk_b33c6e275dc6fe57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subcategory_category ADD CONSTRAINT fk_b33c6e2712469de2 FOREIGN KEY (category_id) REFERENCES mia_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_category ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category DROP id3pl');
        $this->addSql('ALTER TABLE mia_category ALTER visible DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_sub_category ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_sub_category DROP id3pl');
        $this->addSql('ALTER TABLE mia_sub_category ALTER visible DROP DEFAULT');
    }
}
