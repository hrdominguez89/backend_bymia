<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221114021718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE mia_shopping_cart_id_seq CASCADE');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP CONSTRAINT fk_bb0af5ec4584665a');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP CONSTRAINT fk_bb0af5ec9395c3f3');
        $this->addSql('DROP TABLE mia_shopping_cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE mia_shopping_cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mia_shopping_cart (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, product_id BIGINT NOT NULL, quantity INT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_bb0af5ec9395c3f3 ON mia_shopping_cart (customer_id)');
        $this->addSql('CREATE INDEX idx_bb0af5ec4584665a ON mia_shopping_cart (product_id)');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD CONSTRAINT fk_bb0af5ec4584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD CONSTRAINT fk_bb0af5ec9395c3f3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
