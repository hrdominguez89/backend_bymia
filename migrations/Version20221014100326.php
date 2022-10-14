<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221014100326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_category ADD description TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD nomenclature VARCHAR(3) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD visible BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category DROP description');
        $this->addSql('ALTER TABLE mia_category DROP nomenclature');
        $this->addSql('ALTER TABLE mia_category DROP created_at');
        $this->addSql('ALTER TABLE mia_category DROP updated_at');
        $this->addSql('ALTER TABLE mia_category DROP visible');
    }
}
