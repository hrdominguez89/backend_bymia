<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220814203704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE countries_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE countries (id INT NOT NULL, name VARCHAR(100) NOT NULL, iso VARCHAR(3) DEFAULT NULL, numeric_code VARCHAR(3) DEFAULT NULL, iso2 VARCHAR(2) DEFAULT NULL, phonecode VARCHAR(255) DEFAULT NULL, capital VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, currency_name VARCHAR(255) DEFAULT NULL, currency_symbol VARCHAR(255) DEFAULT NULL, tld VARCHAR(255) DEFAULT NULL, native VARCHAR(255) DEFAULT NULL, region VARCHAR(255) DEFAULT NULL, subregion VARCHAR(255) DEFAULT NULL, timezones TEXT DEFAULT NULL, translations TEXT DEFAULT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, emoji VARCHAR(191) DEFAULT NULL, emoji_u VARCHAR(191) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, flag SMALLINT DEFAULT NULL, wiki_data_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE countries_id_seq CASCADE');
        $this->addSql('DROP TABLE countries');
    }
}
