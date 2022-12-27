<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221223051808 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE email_queue_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE email_status_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE email_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE email_queue (id INT NOT NULL, email_type_id INT NOT NULL, email_status_id INT NOT NULL, email_to VARCHAR(255) NOT NULL, parameters JSON NOT NULL, send_attempts SMALLINT DEFAULT 0 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, sent_on TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, error_message TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E2B03EEC788CE45C ON email_queue (email_type_id)');
        $this->addSql('CREATE INDEX IDX_E2B03EEC64FC9F96 ON email_queue (email_status_id)');
        $this->addSql('CREATE TABLE email_status_type (id INT NOT NULL, name VARCHAR(9) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE email_types (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE email_queue ADD CONSTRAINT FK_E2B03EEC788CE45C FOREIGN KEY (email_type_id) REFERENCES email_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE email_queue ADD CONSTRAINT FK_E2B03EEC64FC9F96 FOREIGN KEY (email_status_id) REFERENCES email_status_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql("INSERT INTO email_types (id,name) VALUES (1,'Validation'),(2,'Welcome'),(3,'Forget password'),(4,'Password change request'),(5,'Password change successful')");
        $this->addSql("INSERT INTO email_status_type (id,name) VALUES (1,'Pending'),(2,'Error'),(3,'Sent'),(4,'Canceled')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE email_queue_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE email_status_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE email_types_id_seq CASCADE');
        $this->addSql('ALTER TABLE email_queue DROP CONSTRAINT FK_E2B03EEC788CE45C');
        $this->addSql('ALTER TABLE email_queue DROP CONSTRAINT FK_E2B03EEC64FC9F96');
        $this->addSql('DROP TABLE email_queue');
        $this->addSql('DROP TABLE email_status_type');
        $this->addSql('DROP TABLE email_types');
    }
}
