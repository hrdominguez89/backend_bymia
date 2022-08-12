<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806035713 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE customer_addresses_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE registration_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer_addresses (id INT NOT NULL, customer_id BIGINT NOT NULL, registration_type_id INT DEFAULT NULL, registration_user_id BIGINT DEFAULT NULL, country VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, street VARCHAR(255) NOT NULL, number_street VARCHAR(255) NOT NULL, floor VARCHAR(10) NOT NULL, department VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, additional_info VARCHAR(255) DEFAULT NULL, favorite_address BOOLEAN NOT NULL, billing_address BOOLEAN NOT NULL, registration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C4378D0C9395C3F3 ON customer_addresses (customer_id)');
        $this->addSql('CREATE INDEX IDX_C4378D0C853DD935 ON customer_addresses (registration_type_id)');
        $this->addSql('CREATE INDEX IDX_C4378D0CE71F8633 ON customer_addresses (registration_user_id)');
        $this->addSql('CREATE TABLE registration_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT FK_C4378D0C9395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT FK_C4378D0C853DD935 FOREIGN KEY (registration_type_id) REFERENCES registration_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT FK_C4378D0CE71F8633 FOREIGN KEY (registration_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT fk_9164b3bdd991282d');
        $this->addSql('DROP INDEX idx_9164b3bdd991282d');
        $this->addSql('ALTER TABLE mia_customer ADD registration_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD registration_user_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD registration_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer RENAME COLUMN customer_type_id TO customer_type_role_id');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BDF0FA8E40 FOREIGN KEY (customer_type_role_id) REFERENCES customers_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BD853DD935 FOREIGN KEY (registration_type_id) REFERENCES registration_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BDE71F8633 FOREIGN KEY (registration_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9164B3BDF0FA8E40 ON mia_customer (customer_type_role_id)');
        $this->addSql('CREATE INDEX IDX_9164B3BD853DD935 ON mia_customer (registration_type_id)');
        $this->addSql('CREATE INDEX IDX_9164B3BDE71F8633 ON mia_customer (registration_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT FK_C4378D0C853DD935');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BD853DD935');
        $this->addSql('DROP SEQUENCE customer_addresses_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE registration_type_id_seq CASCADE');
        $this->addSql('DROP TABLE customer_addresses');
        $this->addSql('DROP TABLE registration_type');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BDF0FA8E40');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BDE71F8633');
        $this->addSql('DROP INDEX IDX_9164B3BDF0FA8E40');
        $this->addSql('DROP INDEX IDX_9164B3BD853DD935');
        $this->addSql('DROP INDEX IDX_9164B3BDE71F8633');
        $this->addSql('ALTER TABLE mia_customer ADD customer_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP customer_type_role_id');
        $this->addSql('ALTER TABLE mia_customer DROP registration_type_id');
        $this->addSql('ALTER TABLE mia_customer DROP registration_user_id');
        $this->addSql('ALTER TABLE mia_customer DROP registration_date');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT fk_9164b3bdd991282d FOREIGN KEY (customer_type_id) REFERENCES customers_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_9164b3bdd991282d ON mia_customer (customer_type_id)');
    }
}
