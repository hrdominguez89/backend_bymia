<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806024640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ADD customer_name VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD lastname VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD country_code_cel_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD state_code_cel_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD cel_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD country_code_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD state_code_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP api_id');
        $this->addSql('ALTER TABLE mia_customer DROP identification');
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
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD identification VARCHAR(255) DEFAULT NULL');
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
        $this->addSql('ALTER TABLE mia_customer DROP customer_name');
        $this->addSql('ALTER TABLE mia_customer DROP lastname');
        $this->addSql('ALTER TABLE mia_customer DROP country_code_cel_phone');
        $this->addSql('ALTER TABLE mia_customer DROP state_code_cel_phone');
        $this->addSql('ALTER TABLE mia_customer DROP cel_phone');
        $this->addSql('ALTER TABLE mia_customer DROP country_code_phone');
        $this->addSql('ALTER TABLE mia_customer DROP state_code_phone');
        $this->addSql('ALTER TABLE mia_customer DROP phone');
    }
}
