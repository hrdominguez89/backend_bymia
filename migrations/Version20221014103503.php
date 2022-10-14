<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014103503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand DROP nomenclature');
        $this->addSql('ALTER TABLE mia_brand DROP created_at');
        $this->addSql('ALTER TABLE mia_brand DROP updated_at');
        $this->addSql('ALTER TABLE mia_brand ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_brand ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_category ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_category ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_sub_category ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_sub_category ALTER slug TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_tag DROP visible');
        $this->addSql('ALTER TABLE mia_tag DROP created_at');
        $this->addSql('ALTER TABLE mia_tag DROP updated_at');
        $this->addSql('ALTER TABLE mia_tag ALTER name TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mia_tag ALTER slug TYPE VARCHAR(255)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_category ALTER slug TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_tag ADD visible BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_tag ALTER slug TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_brand ADD nomenclature VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_brand ALTER name TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE mia_brand ALTER slug TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE mia_sub_category ALTER name TYPE VARCHAR(100)');
        $this->addSql('ALTER TABLE mia_sub_category ALTER slug TYPE VARCHAR(100)');
    }
}
