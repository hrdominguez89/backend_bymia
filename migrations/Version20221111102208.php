<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111102208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37cd9cfb16');
        $this->addSql('DROP SEQUENCE product_status_type_id_seq CASCADE');
        $this->addSql('DROP TABLE product_status_type');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea37887793b6');
        $this->addSql('DROP INDEX idx_540dea37887793b6');
        $this->addSql('DROP INDEX idx_540dea37cd9cfb16');
        $this->addSql('ALTER TABLE mia_product ADD description_en TEXT NOT NULL');
        $this->addSql('ALTER TABLE mia_product DROP condition_id');
        $this->addSql('ALTER TABLE mia_product DROP status_type_id');
        $this->addSql('ALTER TABLE mia_product ALTER onhand SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER commited SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER incomming SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER available SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER title SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN description TO description_es');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE product_status_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_status_type (id INT NOT NULL, name VARCHAR(15) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE mia_product ADD condition_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD status_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP description_en');
        $this->addSql('ALTER TABLE mia_product ALTER title DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER onhand DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER commited DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER incomming DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER available DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN description_es TO description');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37cd9cfb16 FOREIGN KEY (status_type_id) REFERENCES product_status_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea37887793b6 FOREIGN KEY (condition_id) REFERENCES product_condition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea37887793b6 ON mia_product (condition_id)');
        $this->addSql('CREATE INDEX idx_540dea37cd9cfb16 ON mia_product (status_type_id)');
    }
}
