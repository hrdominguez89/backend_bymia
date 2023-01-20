<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230120173412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD status_sent_3pl_id INT DEFAULT 1');
        $this->addSql("UPDATE mia_product SET status_sent_3pl_id = 1 WHERE status_sent_3pl_id IS NULL");
        $this->addSql('ALTER TABLE mia_product ALTER COLUMN status_sent_3pl_id SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD attempts_send_3pl SMALLINT DEFAULT 0');
        $this->addSql("UPDATE mia_product SET attempts_send_3pl = 0 WHERE attempts_send_3pl IS NULL");
        $this->addSql('ALTER TABLE mia_product ALTER COLUMN attempts_send_3pl SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD error_message_3pl TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37C5B9A28C FOREIGN KEY (status_sent_3pl_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA37C5B9A28C ON mia_product (status_sent_3pl_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37C5B9A28C');
        $this->addSql('DROP INDEX IDX_540DEA37C5B9A28C');
        $this->addSql('ALTER TABLE mia_product DROP status_sent_3pl_id');
        $this->addSql('ALTER TABLE mia_product DROP attempts_send_3pl');
        $this->addSql('ALTER TABLE mia_product DROP error_message_3pl');
    }
}
