<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307031023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_products ADD currency_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C38248176 FOREIGN KEY (currency_id) REFERENCES mia_currency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_749C879C38248176 ON orders_products (currency_id)');
        $this->addSql('UPDATE orders_products set currency_id=1');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879C38248176');
        $this->addSql('DROP INDEX IDX_749C879C38248176');
        $this->addSql('ALTER TABLE orders_products DROP currency_id');
    }
}
