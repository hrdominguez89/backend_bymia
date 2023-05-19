<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519043020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD status_sent_crm_id INT');
        $this->addSql('ALTER TABLE orders ADD attemps_send_crm SMALLINT');
        $this->addSql('UPDATE orders SET status_sent_crm_id = 3 WHERE status_sent_crm_id IS NULL');
        $this->addSql('UPDATE orders SET attemps_send_crm = 1 WHERE attemps_send_crm IS NULL');
        $this->addSql('ALTER TABLE orders ALTER status_sent_crm_id SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER attemps_send_crm SET NOT NULL');
        $this->addSql('ALTER TABLE orders ADD error_message_crm VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEB4B9E7E FOREIGN KEY (status_sent_crm_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEEB4B9E7E ON orders (status_sent_crm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEB4B9E7E');
        $this->addSql('DROP INDEX IDX_E52FFDEEB4B9E7E');
        $this->addSql('ALTER TABLE orders DROP status_sent_crm_id');
        $this->addSql('ALTER TABLE orders DROP attemps_send_crm');
        $this->addSql('ALTER TABLE orders DROP error_message_crm');
    }
}
