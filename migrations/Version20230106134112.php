<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106134112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_category ADD status_sent_3pl_id INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE mia_category ADD attempts_send_3pl SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_category ADD error_message_3pl TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ADD CONSTRAINT FK_16112475C5B9A28C FOREIGN KEY (status_sent_3pl_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_16112475C5B9A28C ON mia_category (status_sent_3pl_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category DROP CONSTRAINT FK_16112475C5B9A28C');
        $this->addSql('DROP INDEX IDX_16112475C5B9A28C');
        $this->addSql('ALTER TABLE mia_category DROP status_sent_3pl_id');
        $this->addSql('ALTER TABLE mia_category DROP attempts_send_3pl');
        $this->addSql('ALTER TABLE mia_category DROP error_message_3pl');
    }
}
