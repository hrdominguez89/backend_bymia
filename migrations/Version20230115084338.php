<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230115084338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE inventory_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE inventory (id INT NOT NULL, warehouse_id INT NOT NULL, id3pl INT NOT NULL, client_id INT NOT NULL, cod VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B12D4A365080ECDE ON inventory (warehouse_id)');
        $this->addSql('ALTER TABLE inventory ADD CONSTRAINT FK_B12D4A365080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea375080ecde');
        $this->addSql('DROP INDEX idx_540dea375080ecde');
        $this->addSql('ALTER TABLE mia_product DROP warehouse_id');
        $this->addSql('ALTER TABLE mia_product ALTER sku SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER onhand SET DEFAULT 0');
        $this->addSql('ALTER TABLE mia_product ALTER commited SET DEFAULT 0');
        $this->addSql('ALTER TABLE mia_product ALTER incomming SET DEFAULT 0');
        $this->addSql('ALTER TABLE mia_product ALTER available SET DEFAULT 0');
        $this->addSql('ALTER TABLE warehouses DROP slug');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE inventory_id_seq CASCADE');
        $this->addSql('ALTER TABLE inventory DROP CONSTRAINT FK_B12D4A365080ECDE');
        $this->addSql('DROP TABLE inventory');
        $this->addSql('ALTER TABLE warehouses ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD warehouse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER sku DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER onhand DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER commited DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER incomming DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER available DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea375080ecde FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea375080ecde ON mia_product (warehouse_id)');
    }
}
