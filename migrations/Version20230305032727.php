<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305032727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE shopping_cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_type_shopping_cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE shopping_cart (id INT NOT NULL, customer_id BIGINT NOT NULL, product_id BIGINT NOT NULL, status_id INT NOT NULL, favorite_id BIGINT DEFAULT NULL, quantity INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_72AAD4F69395C3F3 ON shopping_cart (customer_id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F64584665A ON shopping_cart (product_id)');
        $this->addSql('CREATE INDEX IDX_72AAD4F66BF700BD ON shopping_cart (status_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72AAD4F6AA17481D ON shopping_cart (favorite_id)');
        $this->addSql('CREATE TABLE status_type_shopping_cart (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO status_type_shopping_cart (id,name) VALUES (1,'Activo')");
        $this->addSql("INSERT INTO status_type_shopping_cart (id,name) VALUES (2,'Eliminado')");
        $this->addSql("INSERT INTO status_type_shopping_cart (id,name) VALUES (3,'En orden')");
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F69395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F64584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F66BF700BD FOREIGN KEY (status_id) REFERENCES status_type_shopping_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6AA17481D FOREIGN KEY (favorite_id) REFERENCES mia_favorite_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE shopping_cart_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_type_shopping_cart_id_seq CASCADE');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F69395C3F3');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F64584665A');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F66BF700BD');
        $this->addSql('ALTER TABLE shopping_cart DROP CONSTRAINT FK_72AAD4F6AA17481D');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE status_type_shopping_cart');
    }
}
