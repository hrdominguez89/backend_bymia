<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203100833 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee38f5dd9b');
        $this->addSql('DROP INDEX idx_e52ffdee38f5dd9b');
        $this->addSql('ALTER TABLE orders RENAME COLUMN bill_address_id_id TO bill_address_id');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5B8A2B31 FOREIGN KEY (bill_address_id) REFERENCES customer_addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEE5B8A2B31 ON orders (bill_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE5B8A2B31');
        $this->addSql('DROP INDEX IDX_E52FFDEE5B8A2B31');
        $this->addSql('ALTER TABLE orders RENAME COLUMN bill_address_id TO bill_address_id_id');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee38f5dd9b FOREIGN KEY (bill_address_id_id) REFERENCES customer_addresses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdee38f5dd9b ON orders (bill_address_id_id)');
    }
}
