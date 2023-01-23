<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123055901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE guide_numbers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE items_guide_number_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE orders_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_order_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE guide_numbers (id INT NOT NULL, number_order_id INT NOT NULL, number VARCHAR(255) NOT NULL, courier VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_438850D1D8D98C7 ON guide_numbers (number_order_id)');
        $this->addSql('CREATE TABLE items_guide_number (id INT NOT NULL, product_id BIGINT NOT NULL, guide_number_id INT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BFB5E8BA4584665A ON items_guide_number (product_id)');
        $this->addSql('CREATE INDEX IDX_BFB5E8BA7D263EF6 ON items_guide_number (guide_number_id)');
        $this->addSql('CREATE TABLE orders (id INT NOT NULL, customer_id BIGINT NOT NULL, customer_type_id INT NOT NULL, customer_phone_code_id INT NOT NULL, bill_address_id_id INT NOT NULL, biil_country_id INT NOT NULL, bill_state_id INT DEFAULT NULL, bill_city_id INT DEFAULT NULL, status_id INT NOT NULL, customer_name VARCHAR(255) NOT NULL, customer_email VARCHAR(255) NOT NULL, cel_phone_customer VARCHAR(255) NOT NULL, phone_customer VARCHAR(255) DEFAULT NULL, customer_identity_type VARCHAR(255) NOT NULL, customer_identity_number VARCHAR(255) NOT NULL, international_shipping BOOLEAN NOT NULL, shipping BOOLEAN NOT NULL, bill_file VARCHAR(255) DEFAULT NULL, payment_file VARCHAR(255) DEFAULT NULL, payment_received_file VARCHAR(255) DEFAULT NULL, debit_credit_note_file VARCHAR(255) DEFAULT NULL, paypal_transaction_code VARCHAR(255) DEFAULT NULL, bill_address TEXT NOT NULL, bill_postal_code VARCHAR(255) NOT NULL, bill_additional_info TEXT DEFAULT NULL, subtotal DOUBLE PRECISION NOT NULL, product_discount DOUBLE PRECISION NOT NULL, promotional_code_discount DOUBLE PRECISION NOT NULL, tax DOUBLE PRECISION NOT NULL, shipping_cost DOUBLE PRECISION NOT NULL, shipping_discount DOUBLE PRECISION NOT NULL, paypal_service_cost DOUBLE PRECISION NOT NULL, total_order DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E52FFDEE9395C3F3 ON orders (customer_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEED991282D ON orders (customer_type_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEED3FD0226 ON orders (customer_phone_code_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE38F5DD9B ON orders (bill_address_id_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4A3D0927 ON orders (biil_country_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE8824F200 ON orders (bill_state_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE7F6F56B0 ON orders (bill_city_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE6BF700BD ON orders (status_id)');
        $this->addSql('CREATE TABLE orders_products (id INT NOT NULL, number_order_id INT NOT NULL, product_id BIGINT NOT NULL, name VARCHAR(255) NOT NULL, sku VARCHAR(40) NOT NULL, part_number VARCHAR(255) DEFAULT NULL, cod VARCHAR(255) DEFAULT NULL, weight VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_749C879CD8D98C7 ON orders_products (number_order_id)');
        $this->addSql('CREATE INDEX IDX_749C879C4584665A ON orders_products (product_id)');
        $this->addSql('CREATE TABLE status_order_type (id INT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO status_order_type (id,name) VALUES (1,'OPEN'),(2,'CONFIRMED'),(3,'CANCELED'),(4,'PICKED'),(5,'PACKED'),(6,'PARTIALLY SHIPPED'),(7,'SHIPPED')");
        $this->addSql('ALTER TABLE guide_numbers ADD CONSTRAINT FK_438850D1D8D98C7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items_guide_number ADD CONSTRAINT FK_BFB5E8BA4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE items_guide_number ADD CONSTRAINT FK_BFB5E8BA7D263EF6 FOREIGN KEY (guide_number_id) REFERENCES guide_numbers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED991282D FOREIGN KEY (customer_type_id) REFERENCES customers_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEED3FD0226 FOREIGN KEY (customer_phone_code_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE38F5DD9B FOREIGN KEY (bill_address_id_id) REFERENCES customer_addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4A3D0927 FOREIGN KEY (biil_country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE8824F200 FOREIGN KEY (bill_state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE7F6F56B0 FOREIGN KEY (bill_city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE6BF700BD FOREIGN KEY (status_id) REFERENCES status_order_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879CD8D98C7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE guide_numbers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE items_guide_number_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE orders_products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_order_type_id_seq CASCADE');
        $this->addSql('ALTER TABLE guide_numbers DROP CONSTRAINT FK_438850D1D8D98C7');
        $this->addSql('ALTER TABLE items_guide_number DROP CONSTRAINT FK_BFB5E8BA4584665A');
        $this->addSql('ALTER TABLE items_guide_number DROP CONSTRAINT FK_BFB5E8BA7D263EF6');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE9395C3F3');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEED991282D');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEED3FD0226');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE38F5DD9B');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE4A3D0927');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE8824F200');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE7F6F56B0');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE6BF700BD');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879CD8D98C7');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879C4584665A');
        $this->addSql('DROP TABLE guide_numbers');
        $this->addSql('DROP TABLE items_guide_number');
        $this->addSql('DROP TABLE orders');
        $this->addSql('DROP TABLE orders_products');
        $this->addSql('DROP TABLE status_order_type');
    }
}
