<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211020032826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mia_customer_coupon_discount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_order_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mia_customer_coupon_discount (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, percent BOOLEAN NOT NULL, discount DOUBLE PRECISION NOT NULL, coupon VARCHAR(255) NOT NULL, date_apply TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, applied BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EEC086F79395C3F3 ON mia_customer_coupon_discount (customer_id)');
        $this->addSql('CREATE TABLE mia_order_items (id BIGINT NOT NULL, order_id BIGINT NOT NULL, pid BIGINT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, image VARCHAR(500) DEFAULT NULL, price DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, total DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DFE5AE9B8D9F6D38 ON mia_order_items (order_id)');
        $this->addSql('ALTER TABLE mia_customer_coupon_discount ADD CONSTRAINT FK_EEC086F79395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_order_items ADD CONSTRAINT FK_DFE5AE9B8D9F6D38 FOREIGN KEY (order_id) REFERENCES mia_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_first_name VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_last_name VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_company_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_country VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_street_address VARCHAR(500) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_city VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_state VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_postcode VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_email VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD billing_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP id_api');
        $this->addSql('ALTER TABLE mia_customer DROP type');
        $this->addSql('ALTER TABLE mia_customer DROP last_name');
        $this->addSql('ALTER TABLE mia_customer DROP country');
        $this->addSql('ALTER TABLE mia_customer DROP province');
        $this->addSql('ALTER TABLE mia_customer DROP municipality');
        $this->addSql('ALTER TABLE mia_customer DROP direction');
        $this->addSql('ALTER TABLE mia_customer DROP postal_code');
        $this->addSql('ALTER TABLE mia_customer DROP home_phone');
        $this->addSql('ALTER TABLE mia_customer DROP cell_phone');
        $this->addSql('ALTER TABLE mia_customer DROP retire_office');
        $this->addSql('ALTER TABLE mia_order ADD total DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD shipping DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD handling DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD insurance DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD tax_total DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD shipping_discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD quantity INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_first_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_last_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_company_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_country_iso VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_country VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_city VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_state VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_email VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_b_phone VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_first_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_last_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_company_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_country_iso VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_country VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_street_address VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_city VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_state VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_postcode VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_email VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_s_phone VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order DROP reference_id');
        $this->addSql('ALTER TABLE mia_order DROP checkout_first_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_last_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_company_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_country');
        $this->addSql('ALTER TABLE mia_order DROP checkout_address');
        $this->addSql('ALTER TABLE mia_order DROP checkout_city');
        $this->addSql('ALTER TABLE mia_order DROP checkout_state');
        $this->addSql('ALTER TABLE mia_order DROP checkout_email');
        $this->addSql('ALTER TABLE mia_order DROP checkout_phone');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN checkout_amount TO sub_total');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN checkout_method TO payment_method');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN checkout_date TO date');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN checkout_street_address TO checkout_b_street_address');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN checkout_postcode TO checkout_b_postcode');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN checkout_different_address TO different_address');
        $this->addSql('ALTER TABLE mia_product ALTER parent_id DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP CONSTRAINT fk_bb0af5ec338af765');
        $this->addSql('DROP INDEX idx_bb0af5ec338af765');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP shopping_order_id');
        $this->addSql('ALTER TABLE mia_social_network ADD type VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE mia_social_network ADD icon VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE mia_customer_coupon_discount_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_order_items_id_seq CASCADE');
        $this->addSql('DROP TABLE mia_customer_coupon_discount');
        $this->addSql('DROP TABLE mia_order_items');
        $this->addSql('ALTER TABLE mia_social_network DROP type');
        $this->addSql('ALTER TABLE mia_social_network DROP icon');
        $this->addSql('ALTER TABLE mia_customer ADD id_api VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD last_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD province VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD municipality VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD direction VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD postal_code VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD home_phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD cell_phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD retire_office TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP api_id');
        $this->addSql('ALTER TABLE mia_customer DROP billing_first_name');
        $this->addSql('ALTER TABLE mia_customer DROP billing_last_name');
        $this->addSql('ALTER TABLE mia_customer DROP billing_company_name');
        $this->addSql('ALTER TABLE mia_customer DROP billing_country');
        $this->addSql('ALTER TABLE mia_customer DROP billing_street_address');
        $this->addSql('ALTER TABLE mia_customer DROP billing_address');
        $this->addSql('ALTER TABLE mia_customer DROP billing_city');
        $this->addSql('ALTER TABLE mia_customer DROP billing_state');
        $this->addSql('ALTER TABLE mia_customer DROP billing_postcode');
        $this->addSql('ALTER TABLE mia_customer DROP billing_email');
        $this->addSql('ALTER TABLE mia_customer DROP billing_phone');
        $this->addSql('ALTER TABLE mia_product ALTER parent_id SET NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD reference_id VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_first_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_last_name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_company_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_country VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_street_address VARCHAR(500) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_address VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_city VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_state VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_postcode VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_email VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_phone VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_order ADD checkout_amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_order DROP sub_total');
        $this->addSql('ALTER TABLE mia_order DROP total');
        $this->addSql('ALTER TABLE mia_order DROP shipping');
        $this->addSql('ALTER TABLE mia_order DROP handling');
        $this->addSql('ALTER TABLE mia_order DROP insurance');
        $this->addSql('ALTER TABLE mia_order DROP tax_total');
        $this->addSql('ALTER TABLE mia_order DROP shipping_discount');
        $this->addSql('ALTER TABLE mia_order DROP discount');
        $this->addSql('ALTER TABLE mia_order DROP quantity');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_first_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_last_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_company_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_country_iso');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_country');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_street_address');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_address');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_city');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_state');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_postcode');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_email');
        $this->addSql('ALTER TABLE mia_order DROP checkout_b_phone');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_first_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_last_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_company_name');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_country_iso');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_country');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_street_address');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_address');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_city');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_state');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_postcode');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_email');
        $this->addSql('ALTER TABLE mia_order DROP checkout_s_phone');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN payment_method TO checkout_method');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN different_address TO checkout_different_address');
        $this->addSql('ALTER TABLE mia_order RENAME COLUMN date TO checkout_date');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD shopping_order_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD CONSTRAINT fk_bb0af5ec338af765 FOREIGN KEY (shopping_order_id) REFERENCES mia_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_bb0af5ec338af765 ON mia_shopping_cart (shopping_order_id)');
    }
}
