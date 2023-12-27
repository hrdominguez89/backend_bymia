<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231227163337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE status_type_transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transactions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE status_type_transaction (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE transactions (id INT NOT NULL, number_order_id INT NOT NULL, status_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, tax DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, error_message TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_EAA81A4CD8D98C7 ON transactions (number_order_id)');
        $this->addSql('CREATE INDEX IDX_EAA81A4C6BF700BD ON transactions (status_id)');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CD8D98C7 FOREIGN KEY (number_order_id) REFERENCES orders (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C6BF700BD FOREIGN KEY (status_id) REFERENCES status_type_transaction (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("INSERT INTO status_type_transaction (id,name) VALUES (1,'New'),(2,'Canceled'),(3,'Accepted'),(4,'Rejected')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE status_type_transaction_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transactions_id_seq CASCADE');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4CD8D98C7');
        $this->addSql('ALTER TABLE transactions DROP CONSTRAINT FK_EAA81A4C6BF700BD');
        $this->addSql('DROP TABLE status_type_transaction');
        $this->addSql('DROP TABLE transactions');
    }
}
