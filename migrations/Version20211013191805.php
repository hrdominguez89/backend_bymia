<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013191805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE mia_about_us_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_advertisements_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_brand_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_configuration_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_contact_us_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_coupon_discount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_cover_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_currency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_currency_change_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_favorite_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_message_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_newsletter_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_order_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_paypal_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_product_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_product_reviews_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_product_specification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_product_subcategories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_product_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_shopping_cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_social_network_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_specification_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_specification_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_sub_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_terms_coditions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_time_delay_store_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE mia_view_orders_summary_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mia_about_us (id BIGINT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_advertisements (id BIGINT NOT NULL, src1 VARCHAR(255) DEFAULT NULL, src_sm1 VARCHAR(255) DEFAULT NULL, src2 VARCHAR(255) DEFAULT NULL, src_sm2 VARCHAR(255) DEFAULT NULL, src3 VARCHAR(255) DEFAULT NULL, src_sm3 VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_brand (id BIGINT NOT NULL, api_id VARCHAR(255) DEFAULT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, image TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_category (id BIGINT NOT NULL, name VARCHAR(100) NOT NULL, path VARCHAR(255) DEFAULT NULL, type VARCHAR(10) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, api_id VARCHAR(255) DEFAULT NULL, image TEXT DEFAULT NULL, items INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_configuration (id BIGINT NOT NULL, title VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, image TEXT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_contact_us (id BIGINT NOT NULL, description TEXT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone_main VARCHAR(255) DEFAULT NULL, phone_other VARCHAR(255) DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_coupon_discount (id BIGINT NOT NULL, percent BOOLEAN NOT NULL, number_of_uses INT NOT NULL, nro VARCHAR(255) DEFAULT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_cover_image (id BIGINT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, name_btn VARCHAR(255) DEFAULT NULL, link_btn VARCHAR(255) DEFAULT NULL, main BOOLEAN NOT NULL, image_lg VARCHAR(255) NOT NULL, image_sm VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_currency (id BIGINT NOT NULL, api_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(10) DEFAULT NULL, main BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_790BB58B5E237E06 ON mia_currency (name)');
        $this->addSql('CREATE TABLE mia_currency_change (id BIGINT NOT NULL, currency_id BIGINT DEFAULT NULL, value DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1895FB6A38248176 ON mia_currency_change (currency_id)');
        $this->addSql('CREATE TABLE mia_customer (id BIGINT NOT NULL, id_api VARCHAR(255) DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, identification VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, country VARCHAR(255) NOT NULL, province VARCHAR(255) DEFAULT NULL, municipality VARCHAR(255) DEFAULT NULL, direction VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(255) DEFAULT NULL, home_phone VARCHAR(255) DEFAULT NULL, cell_phone VARCHAR(255) DEFAULT NULL, retire_office TEXT DEFAULT NULL, email VARCHAR(512) NOT NULL, password VARCHAR(512) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_favorite_product (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, product_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C5E63AA79395C3F3 ON mia_favorite_product (customer_id)');
        $this->addSql('CREATE INDEX IDX_C5E63AA74584665A ON mia_favorite_product (product_id)');
        $this->addSql('CREATE TABLE mia_message (id INT NOT NULL, name VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, subject VARCHAR(50) NOT NULL, message TEXT NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, new BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_newsletter (id BIGINT NOT NULL, email TEXT NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_order (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, reference_id VARCHAR(255) NOT NULL, checkout_method VARCHAR(100) DEFAULT NULL, checkout_id VARCHAR(255) DEFAULT NULL, checkout_first_name VARCHAR(100) NOT NULL, checkout_last_name VARCHAR(100) NOT NULL, checkout_company_name VARCHAR(255) DEFAULT NULL, checkout_country VARCHAR(100) NOT NULL, checkout_street_address VARCHAR(500) NOT NULL, checkout_address VARCHAR(255) DEFAULT NULL, checkout_city VARCHAR(100) NOT NULL, checkout_state VARCHAR(100) NOT NULL, checkout_postcode VARCHAR(50) NOT NULL, checkout_email VARCHAR(100) NOT NULL, checkout_phone VARCHAR(100) NOT NULL, checkout_comment VARCHAR(255) DEFAULT NULL, checkout_different_address BOOLEAN NOT NULL, checkout_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, checkout_status VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, checkout_amount DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6793503B9395C3F3 ON mia_order (customer_id)');
        $this->addSql('CREATE TABLE mia_paypal (id BIGINT NOT NULL, client_id VARCHAR(255) DEFAULT NULL, client_secret VARCHAR(255) DEFAULT NULL, client_id_sand_box VARCHAR(255) DEFAULT NULL, client_secret_sand_box VARCHAR(255) DEFAULT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, sand_box BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_product (id BIGINT NOT NULL, brand_id BIGINT DEFAULT NULL, parent_id VARCHAR(255) NOT NULL, sku VARCHAR(255) DEFAULT NULL, badges VARCHAR(10) DEFAULT NULL, availability VARCHAR(20) DEFAULT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, image TEXT DEFAULT NULL, description TEXT DEFAULT NULL, stock DOUBLE PRECISION DEFAULT NULL, url VARCHAR(255) DEFAULT NULL, weight DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION DEFAULT NULL, offer_price DOUBLE PRECISION DEFAULT NULL, offer_start_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, offer_end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, html_description TEXT DEFAULT NULL, short_description TEXT DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, length DOUBLE PRECISION DEFAULT NULL, dimensions VARCHAR(255) DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, featured BOOLEAN NOT NULL, sales DOUBLE PRECISION NOT NULL, reviews DOUBLE PRECISION NOT NULL, rating DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_540DEA3744F5D008 ON mia_product (brand_id)');
        $this->addSql('CREATE TABLE mia_product_image (id BIGINT NOT NULL, product_id BIGINT DEFAULT NULL, image TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ADC15E194584665A ON mia_product_image (product_id)');
        $this->addSql('CREATE TABLE mia_product_reviews (id BIGINT NOT NULL, product_id BIGINT NOT NULL, customer_id BIGINT NOT NULL, rating INT NOT NULL, message TEXT NOT NULL, date_created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_44EDD39A4584665A ON mia_product_reviews (product_id)');
        $this->addSql('CREATE INDEX IDX_44EDD39A9395C3F3 ON mia_product_reviews (customer_id)');
        $this->addSql('CREATE TABLE mia_product_specification (id BIGINT NOT NULL, product_id BIGINT NOT NULL, specification_id BIGINT NOT NULL, value VARCHAR(255) NOT NULL, custom_fields_type VARCHAR(255) NOT NULL, custom_fields_value VARCHAR(255) NOT NULL, create_variation BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AD7A82E74584665A ON mia_product_specification (product_id)');
        $this->addSql('CREATE INDEX IDX_AD7A82E7908E2FFE ON mia_product_specification (specification_id)');
        $this->addSql('CREATE TABLE mia_product_subcategories (id BIGINT NOT NULL, product_id BIGINT NOT NULL, sub_categoria_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C62739B64584665A ON mia_product_subcategories (product_id)');
        $this->addSql('CREATE INDEX IDX_C62739B624C5374C ON mia_product_subcategories (sub_categoria_id)');
        $this->addSql('CREATE TABLE mia_product_tag (id BIGINT NOT NULL, product_id BIGINT DEFAULT NULL, tag_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5EC3D0B74584665A ON mia_product_tag (product_id)');
        $this->addSql('CREATE INDEX IDX_5EC3D0B7BAD26311 ON mia_product_tag (tag_id)');
        $this->addSql('CREATE TABLE mia_reset_password_request (id BIGINT NOT NULL, user_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7AB042BFA76ED395 ON mia_reset_password_request (user_id)');
        $this->addSql('CREATE TABLE mia_shopping_cart (id BIGINT NOT NULL, customer_id BIGINT NOT NULL, product_id BIGINT NOT NULL, shopping_order_id BIGINT DEFAULT NULL, quantity INT NOT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BB0AF5EC9395C3F3 ON mia_shopping_cart (customer_id)');
        $this->addSql('CREATE INDEX IDX_BB0AF5EC4584665A ON mia_shopping_cart (product_id)');
        $this->addSql('CREATE INDEX IDX_BB0AF5EC338AF765 ON mia_shopping_cart (shopping_order_id)');
        $this->addSql('CREATE TABLE mia_social_network (id BIGINT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(50) NOT NULL, url VARCHAR(255) DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_specification (id BIGINT NOT NULL, specification_type_id BIGINT NOT NULL, api_id VARCHAR(255) DEFAULT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, filter BOOLEAN DEFAULT NULL, default_custom_fields_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C79F3B8086F60D4E ON mia_specification (specification_type_id)');
        $this->addSql('CREATE TABLE mia_specification_type (id BIGINT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, default_custom_fields_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_sub_category (id BIGINT NOT NULL, categoria_id BIGINT NOT NULL, name VARCHAR(100) NOT NULL, path VARCHAR(255) DEFAULT NULL, type VARCHAR(10) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, api_id VARCHAR(255) DEFAULT NULL, image TEXT DEFAULT NULL, items INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_10E26BEB3397707A ON mia_sub_category (categoria_id)');
        $this->addSql('CREATE TABLE mia_tag (id BIGINT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, api_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_terms_coditions (id BIGINT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_time_delay_store (id BIGINT NOT NULL, api_id VARCHAR(255) DEFAULT NULL, name VARCHAR(100) NOT NULL, tiempo DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_user (id BIGINT NOT NULL, email VARCHAR(512) NOT NULL, password VARCHAR(512) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, roles VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE mia_view_orders_summary (id BIGINT NOT NULL, date VARCHAR(100) NOT NULL, cant INT NOT NULL, amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE mia_currency_change ADD CONSTRAINT FK_1895FB6A38248176 FOREIGN KEY (currency_id) REFERENCES mia_currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_favorite_product ADD CONSTRAINT FK_C5E63AA79395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_favorite_product ADD CONSTRAINT FK_C5E63AA74584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_order ADD CONSTRAINT FK_6793503B9395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3744F5D008 FOREIGN KEY (brand_id) REFERENCES mia_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_image ADD CONSTRAINT FK_ADC15E194584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_reviews ADD CONSTRAINT FK_44EDD39A4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_reviews ADD CONSTRAINT FK_44EDD39A9395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_specification ADD CONSTRAINT FK_AD7A82E74584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_specification ADD CONSTRAINT FK_AD7A82E7908E2FFE FOREIGN KEY (specification_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_subcategories ADD CONSTRAINT FK_C62739B64584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_subcategories ADD CONSTRAINT FK_C62739B624C5374C FOREIGN KEY (sub_categoria_id) REFERENCES mia_sub_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_tag ADD CONSTRAINT FK_5EC3D0B74584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product_tag ADD CONSTRAINT FK_5EC3D0B7BAD26311 FOREIGN KEY (tag_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_reset_password_request ADD CONSTRAINT FK_7AB042BFA76ED395 FOREIGN KEY (user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD CONSTRAINT FK_BB0AF5EC9395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD CONSTRAINT FK_BB0AF5EC4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_shopping_cart ADD CONSTRAINT FK_BB0AF5EC338AF765 FOREIGN KEY (shopping_order_id) REFERENCES mia_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_specification ADD CONSTRAINT FK_C79F3B8086F60D4E FOREIGN KEY (specification_type_id) REFERENCES mia_specification_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_sub_category ADD CONSTRAINT FK_10E26BEB3397707A FOREIGN KEY (categoria_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3744F5D008');
        $this->addSql('ALTER TABLE mia_sub_category DROP CONSTRAINT FK_10E26BEB3397707A');
        $this->addSql('ALTER TABLE mia_currency_change DROP CONSTRAINT FK_1895FB6A38248176');
        $this->addSql('ALTER TABLE mia_favorite_product DROP CONSTRAINT FK_C5E63AA79395C3F3');
        $this->addSql('ALTER TABLE mia_order DROP CONSTRAINT FK_6793503B9395C3F3');
        $this->addSql('ALTER TABLE mia_product_reviews DROP CONSTRAINT FK_44EDD39A9395C3F3');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP CONSTRAINT FK_BB0AF5EC9395C3F3');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP CONSTRAINT FK_BB0AF5EC338AF765');
        $this->addSql('ALTER TABLE mia_favorite_product DROP CONSTRAINT FK_C5E63AA74584665A');
        $this->addSql('ALTER TABLE mia_product_image DROP CONSTRAINT FK_ADC15E194584665A');
        $this->addSql('ALTER TABLE mia_product_reviews DROP CONSTRAINT FK_44EDD39A4584665A');
        $this->addSql('ALTER TABLE mia_product_specification DROP CONSTRAINT FK_AD7A82E74584665A');
        $this->addSql('ALTER TABLE mia_product_subcategories DROP CONSTRAINT FK_C62739B64584665A');
        $this->addSql('ALTER TABLE mia_product_tag DROP CONSTRAINT FK_5EC3D0B74584665A');
        $this->addSql('ALTER TABLE mia_shopping_cart DROP CONSTRAINT FK_BB0AF5EC4584665A');
        $this->addSql('ALTER TABLE mia_product_specification DROP CONSTRAINT FK_AD7A82E7908E2FFE');
        $this->addSql('ALTER TABLE mia_specification DROP CONSTRAINT FK_C79F3B8086F60D4E');
        $this->addSql('ALTER TABLE mia_product_subcategories DROP CONSTRAINT FK_C62739B624C5374C');
        $this->addSql('ALTER TABLE mia_product_tag DROP CONSTRAINT FK_5EC3D0B7BAD26311');
        $this->addSql('ALTER TABLE mia_reset_password_request DROP CONSTRAINT FK_7AB042BFA76ED395');
        $this->addSql('DROP SEQUENCE mia_about_us_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_advertisements_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_brand_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_configuration_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_contact_us_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_coupon_discount_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_cover_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_currency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_currency_change_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_favorite_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_message_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_newsletter_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_order_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_paypal_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_product_image_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_product_reviews_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_product_specification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_product_subcategories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_product_tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_reset_password_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_shopping_cart_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_social_network_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_specification_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_specification_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_sub_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_terms_coditions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_time_delay_store_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE mia_view_orders_summary_id_seq CASCADE');
        $this->addSql('DROP TABLE mia_about_us');
        $this->addSql('DROP TABLE mia_advertisements');
        $this->addSql('DROP TABLE mia_brand');
        $this->addSql('DROP TABLE mia_category');
        $this->addSql('DROP TABLE mia_configuration');
        $this->addSql('DROP TABLE mia_contact_us');
        $this->addSql('DROP TABLE mia_coupon_discount');
        $this->addSql('DROP TABLE mia_cover_image');
        $this->addSql('DROP TABLE mia_currency');
        $this->addSql('DROP TABLE mia_currency_change');
        $this->addSql('DROP TABLE mia_customer');
        $this->addSql('DROP TABLE mia_favorite_product');
        $this->addSql('DROP TABLE mia_message');
        $this->addSql('DROP TABLE mia_newsletter');
        $this->addSql('DROP TABLE mia_order');
        $this->addSql('DROP TABLE mia_paypal');
        $this->addSql('DROP TABLE mia_product');
        $this->addSql('DROP TABLE mia_product_image');
        $this->addSql('DROP TABLE mia_product_reviews');
        $this->addSql('DROP TABLE mia_product_specification');
        $this->addSql('DROP TABLE mia_product_subcategories');
        $this->addSql('DROP TABLE mia_product_tag');
        $this->addSql('DROP TABLE mia_reset_password_request');
        $this->addSql('DROP TABLE mia_shopping_cart');
        $this->addSql('DROP TABLE mia_social_network');
        $this->addSql('DROP TABLE mia_specification');
        $this->addSql('DROP TABLE mia_specification_type');
        $this->addSql('DROP TABLE mia_sub_category');
        $this->addSql('DROP TABLE mia_tag');
        $this->addSql('DROP TABLE mia_terms_coditions');
        $this->addSql('DROP TABLE mia_time_delay_store');
        $this->addSql('DROP TABLE mia_user');
        $this->addSql('DROP TABLE mia_view_orders_summary');
    }
}
