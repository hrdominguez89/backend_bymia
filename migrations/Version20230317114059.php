<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317114059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP payment_file');
        $this->addSql('ALTER TABLE orders DROP payment_received_file');
        $this->addSql('ALTER TABLE orders DROP debit_credit_note_file');
        $this->addSql('ALTER TABLE orders DROP paypal_transaction_code');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders ADD payment_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD payment_received_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD debit_credit_note_file VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD paypal_transaction_code VARCHAR(255) DEFAULT NULL');
    }
}
