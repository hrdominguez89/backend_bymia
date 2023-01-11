<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230106143203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_sub_category ADD category_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD status_sent_3pl_id INT DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD attempts_send_3pl SMALLINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ADD error_message_3pl TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_sub_category ALTER id3pl TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_sub_category ADD CONSTRAINT FK_10E26BEB12469DE2 FOREIGN KEY (category_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_sub_category ADD CONSTRAINT FK_10E26BEBC5B9A28C FOREIGN KEY (status_sent_3pl_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_10E26BEB12469DE2 ON mia_sub_category (category_id)');
        $this->addSql('CREATE INDEX IDX_10E26BEBC5B9A28C ON mia_sub_category (status_sent_3pl_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_sub_category DROP CONSTRAINT FK_10E26BEB12469DE2');
        $this->addSql('ALTER TABLE mia_sub_category DROP CONSTRAINT FK_10E26BEBC5B9A28C');
        $this->addSql('DROP INDEX IDX_10E26BEB12469DE2');
        $this->addSql('DROP INDEX IDX_10E26BEBC5B9A28C');
        $this->addSql('ALTER TABLE mia_sub_category DROP category_id');
        $this->addSql('ALTER TABLE mia_sub_category DROP status_sent_3pl_id');
        $this->addSql('ALTER TABLE mia_sub_category DROP attempts_send_3pl');
        $this->addSql('ALTER TABLE mia_sub_category DROP error_message_3pl');
        $this->addSql('ALTER TABLE mia_sub_category ALTER id3pl TYPE INT');
    }
}
