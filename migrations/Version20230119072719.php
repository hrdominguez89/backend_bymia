<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230119072719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_subcategory DROP CONSTRAINT fk_a1f33a574584665a');
        $this->addSql('ALTER TABLE product_subcategory DROP CONSTRAINT fk_a1f33a575dc6fe57');
        $this->addSql('DROP TABLE product_subcategory');
        $this->addSql('ALTER TABLE mia_product ADD subcategory_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA375DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA375DC6FE57 ON mia_product (subcategory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE product_subcategory (product_id BIGINT NOT NULL, subcategory_id BIGINT NOT NULL, PRIMARY KEY(product_id, subcategory_id))');
        $this->addSql('CREATE INDEX idx_a1f33a575dc6fe57 ON product_subcategory (subcategory_id)');
        $this->addSql('CREATE INDEX idx_a1f33a574584665a ON product_subcategory (product_id)');
        $this->addSql('ALTER TABLE product_subcategory ADD CONSTRAINT fk_a1f33a574584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_subcategory ADD CONSTRAINT fk_a1f33a575dc6fe57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA375DC6FE57');
        $this->addSql('DROP INDEX IDX_540DEA375DC6FE57');
        $this->addSql('ALTER TABLE mia_product DROP subcategory_id');
    }
}
