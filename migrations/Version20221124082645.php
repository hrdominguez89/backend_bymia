<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221124082645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ADD sended_CRM BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP lastname');
        $this->addSql('UPDATE mia_product SET cost=0 where cost is null');
        $this->addSql('ALTER TABLE mia_product ALTER cost SET DEFAULT \'0\'');
        $this->addSql('ALTER TABLE mia_product ALTER cost SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ALTER cost DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER cost DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD lastname VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP sended_CRM');
    }
}
