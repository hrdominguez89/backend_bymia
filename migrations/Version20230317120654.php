<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317120654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE debit_credit_notes_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payments_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payments_received_files_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE payments_transactions_codes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE debit_credit_notes_files (id INT NOT NULL, number_order_id INT NOT NULL, debit_credit_note_file TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7044E413D8D98C7 ON debit_credit_notes_files (number_order_id)');
        $this->addSql('CREATE TABLE payments_files (id INT NOT NULL, order_number_id INT NOT NULL, payment_file TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A12864BB8C26A5E8 ON payments_files (order_number_id)');
        $this->addSql('CREATE TABLE payments_received_files (id INT NOT NULL, order_number_id INT NOT NULL, payment_received_file TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A8B3A4D38C26A5E8 ON payments_received_files (order_number_id)');
        $this->addSql('CREATE TABLE payments_transactions_codes (id INT NOT NULL, order_number_id INT NOT NULL, payment_transaction_code TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAE13E988C26A5E8 ON payments_transactions_codes (order_number_id)');
        $this->addSql('ALTER TABLE debit_credit_notes_files ADD CONSTRAINT FK_7044E413D8D98C7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments_files ADD CONSTRAINT FK_A12864BB8C26A5E8 FOREIGN KEY (order_number_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments_received_files ADD CONSTRAINT FK_A8B3A4D38C26A5E8 FOREIGN KEY (order_number_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE payments_transactions_codes ADD CONSTRAINT FK_EAE13E988C26A5E8 FOREIGN KEY (order_number_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE debit_credit_notes_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_received_files_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE payments_transactions_codes_id_seq CASCADE');
        $this->addSql('ALTER TABLE debit_credit_notes_files DROP CONSTRAINT FK_7044E413D8D98C7');
        $this->addSql('ALTER TABLE payments_files DROP CONSTRAINT FK_A12864BB8C26A5E8');
        $this->addSql('ALTER TABLE payments_received_files DROP CONSTRAINT FK_A8B3A4D38C26A5E8');
        $this->addSql('ALTER TABLE payments_transactions_codes DROP CONSTRAINT FK_EAE13E988C26A5E8');
        $this->addSql('DROP TABLE debit_credit_notes_files');
        $this->addSql('DROP TABLE payments_files');
        $this->addSql('DROP TABLE payments_received_files');
        $this->addSql('DROP TABLE payments_transactions_codes');
        $this->addSql('ALTER TABLE orders ALTER created_at DROP DEFAULT');
    }
}
