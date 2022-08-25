<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825041933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ADD gender_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD date_of_birth DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD google_oauth_uid BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BD37A4F92F FOREIGN KEY (gender_type_id) REFERENCES gender_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9164B3BD37A4F92F ON mia_customer (gender_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BD37A4F92F');
        $this->addSql('DROP INDEX IDX_9164B3BD37A4F92F');
        $this->addSql('ALTER TABLE mia_customer DROP gender_type_id');
        $this->addSql('ALTER TABLE mia_customer DROP date_of_birth');
        $this->addSql('ALTER TABLE mia_customer DROP google_oauth_uid');
    }
}
