<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230117145443 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD inventory_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN title TO name');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA379EEA759 FOREIGN KEY (inventory_id) REFERENCES inventory (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA379EEA759 ON mia_product (inventory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA379EEA759');
        $this->addSql('DROP INDEX IDX_540DEA379EEA759');
        $this->addSql('ALTER TABLE mia_product DROP inventory_id');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN name TO title');
    }
}
