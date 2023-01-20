<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120123848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE images_products_id_seq CASCADE');
        $this->addSql('ALTER TABLE images_products DROP CONSTRAINT fk_109927304584665a');
        $this->addSql('DROP TABLE images_products');
        $this->addSql('ALTER TABLE mia_product_image ALTER principal SET DEFAULT false');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE images_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE images_products (id INT NOT NULL, product_id BIGINT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_109927304584665a ON images_products (product_id)');
        $this->addSql('ALTER TABLE images_products ADD CONSTRAINT fk_109927304584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_image ALTER principal DROP DEFAULT');
    }
}
