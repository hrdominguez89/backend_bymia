<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230103034747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ADD country_phone_code_id INT');
        $this->addSql('UPDATE mia_customer SET country_phone_code_id=11 WHERE country_phone_code_id IS NULL');
        $this->addSql('ALTER TABLE mia_customer ALTER COLUMN country_phone_code_id SET NOT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP country_code_cel_phone');
        $this->addSql('ALTER TABLE mia_customer DROP country_code_phone');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BD442DA0E1 FOREIGN KEY (country_phone_code_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9164B3BD442DA0E1 ON mia_customer (country_phone_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BD442DA0E1');
        $this->addSql('DROP INDEX IDX_9164B3BD442DA0E1');
        $this->addSql('ALTER TABLE mia_customer ADD country_code_cel_phone VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD country_code_phone VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP country_phone_code_id');
    }
}
