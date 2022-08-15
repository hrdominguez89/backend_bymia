<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220814194912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE states DROP CONSTRAINT country_region_final');
        $this->addSql('ALTER TABLE cities DROP CONSTRAINT cities_ibfk_2');
        $this->addSql('ALTER TABLE cities DROP CONSTRAINT cities_ibfk_1');
        $this->addSql('DROP SEQUENCE countries_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE states_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cities_id_seq CASCADE');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE states');
        $this->addSql('DROP TABLE cities');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE countries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE states_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cities_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE countries (id SERIAL NOT NULL, name VARCHAR(100) NOT NULL, iso3 CHAR(3) DEFAULT NULL, numeric_code CHAR(3) DEFAULT NULL, iso2 CHAR(2) DEFAULT NULL, phonecode VARCHAR(255) DEFAULT NULL, capital VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, currency_name VARCHAR(255) DEFAULT NULL, currency_symbol VARCHAR(255) DEFAULT NULL, tld VARCHAR(255) DEFAULT NULL, native VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, subregion VARCHAR(255) DEFAULT NULL, timezones TEXT DEFAULT NULL, translations TEXT DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, emoji VARCHAR(191) DEFAULT NULL, emojiu VARCHAR(191) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, flag SMALLINT DEFAULT 1 NOT NULL, wikidataid VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE states (id SERIAL NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, country_code CHAR(2) NOT NULL, fips_code VARCHAR(255) DEFAULT NULL, iso2 VARCHAR(255) DEFAULT NULL, type VARCHAR(191) DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, flag SMALLINT DEFAULT 1 NOT NULL, wikidataid VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_31C2774DF92F3E70 ON states (country_id)');
        $this->addSql('CREATE TABLE cities (id SERIAL NOT NULL, state_id INT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, state_code VARCHAR(255) NOT NULL, country_code CHAR(2) NOT NULL, latitude NUMERIC(10, 8) NOT NULL, longitude NUMERIC(11, 8) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2014-01-01 06:31:01\' NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, flag SMALLINT DEFAULT 1 NOT NULL, wikidataid VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D95DB16B5D83CC1 ON cities (state_id)');
        $this->addSql('CREATE INDEX IDX_D95DB16BF92F3E70 ON cities (country_id)');
        $this->addSql('ALTER TABLE states ADD CONSTRAINT country_region_final FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cities ADD CONSTRAINT cities_ibfk_1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cities ADD CONSTRAINT cities_ibfk_2 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
