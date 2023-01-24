<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123181616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE specification_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE specification_types (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO specification_types (id,name) VALUES (1,'Resolución de Pantalla'),(2,'Tamaño de pantalla'),(3,'CPU'),(4,'GPU'),(5,'Memoria RAM'),(6,'Tamaño'),(7,'S.O.'),(8,'Condición')");
        $this->addSql('ALTER TABLE mia_category ALTER nomenclature TYPE VARCHAR(3)');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN memory TO storage');
        $this->addSql('TRUNCATE TABLE mia_specification');
        $this->addSql('ALTER TABLE mia_specification ADD specification_type_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_specification DROP api_id');
        $this->addSql('ALTER TABLE mia_specification DROP filter');
        $this->addSql('ALTER TABLE mia_specification ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_specification ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_specification ADD CONSTRAINT FK_C79F3B8086F60D4E FOREIGN KEY (specification_type_id) REFERENCES specification_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C79F3B8086F60D4E ON mia_specification (specification_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_specification DROP CONSTRAINT FK_C79F3B8086F60D4E');
        $this->addSql('DROP SEQUENCE specification_types_id_seq CASCADE');
        $this->addSql('DROP TABLE specification_types');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN storage TO memory');
        $this->addSql('ALTER TABLE mia_category ALTER nomenclature TYPE VARCHAR(2)');
        $this->addSql('DROP INDEX IDX_C79F3B8086F60D4E');
        $this->addSql('ALTER TABLE mia_specification ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_specification ADD filter BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_specification DROP specification_type_id');
        $this->addSql('ALTER TABLE mia_specification ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_specification ALTER slug TYPE VARCHAR(100)');
    }
}
