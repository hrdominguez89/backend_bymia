<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231201012405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_addresses ALTER number_street DROP NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER floor DROP NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER department DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer_addresses ALTER number_street SET NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER floor SET NOT NULL');
        $this->addSql('ALTER TABLE customer_addresses ALTER department SET NOT NULL');
    }
}
