<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306233428 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD currency_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3738248176 FOREIGN KEY (currency_id) REFERENCES mia_currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA3738248176 ON mia_product (currency_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3738248176');
        $this->addSql('DROP INDEX IDX_540DEA3738248176');
        $this->addSql('ALTER TABLE mia_product DROP currency_id');
    }
}
