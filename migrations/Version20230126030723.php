<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126030723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD long_description_es TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD long_description_eng TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP weight');
        $this->addSql('ALTER TABLE mia_product DROP color');
        $this->addSql('ALTER TABLE mia_product DROP screen_resolution');
        $this->addSql('ALTER TABLE mia_product DROP cpu');
        $this->addSql('ALTER TABLE mia_product DROP gpu');
        $this->addSql('ALTER TABLE mia_product DROP ram');
        $this->addSql('ALTER TABLE mia_product DROP storage');
        $this->addSql('ALTER TABLE mia_product DROP screen_size');
        $this->addSql('ALTER TABLE mia_product DROP op_sys');
        $this->addSql('ALTER TABLE mia_product DROP model');
        $this->addSql('ALTER TABLE mia_product DROP conditium');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ADD weight VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD color VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD screen_resolution VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD cpu VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD gpu VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD ram VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD storage VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD screen_size VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD op_sys VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD model VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD conditium VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product DROP long_description_es');
        $this->addSql('ALTER TABLE mia_product DROP long_description_eng');
    }
}
