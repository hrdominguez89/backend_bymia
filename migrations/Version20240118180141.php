<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240118180141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transactions ADD authorization_code INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD tx_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD response_code VARCHAR(2) DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD creditcard_number VARCHAR(30) DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD retrival_reference_number VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE transactions ADD remote_response_code VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transactions DROP authorization_code');
        $this->addSql('ALTER TABLE transactions DROP tx_token');
        $this->addSql('ALTER TABLE transactions DROP response_code');
        $this->addSql('ALTER TABLE transactions DROP creditcard_number');
        $this->addSql('ALTER TABLE transactions DROP retrival_reference_number');
        $this->addSql('ALTER TABLE transactions DROP remote_response_code');
    }
}
