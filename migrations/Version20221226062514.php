<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226062514 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_customer ADD status_sent_crm_id INT');
        $this->addSql("UPDATE mia_customer SET status_sent_crm_id = 1 WHERE status_sent_crm_id IS NULL");
        $this->addSql('ALTER TABLE mia_customer ALTER COLUMN status_sent_crm_id SET NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD status_id INT');
        $this->addSql("UPDATE mia_customer SET status_id = 1 WHERE status_id IS NULL");
        $this->addSql('ALTER TABLE mia_customer ALTER COLUMN status_id SET NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD attempts_send_crm INT');
        $this->addSql("UPDATE mia_customer SET attempts_send_crm = 0 WHERE attempts_send_crm IS NULL");
        $this->addSql('ALTER TABLE mia_customer ALTER COLUMN attempts_send_crm SET NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD error_message_crm VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD verification_code UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD change_password BOOLEAN');
        $this->addSql("UPDATE mia_customer SET change_password = FALSE WHERE change_password IS NULL");
        $this->addSql('ALTER TABLE mia_customer ALTER COLUMN change_password SET NOT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD change_password_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP status');
        $this->addSql('ALTER TABLE mia_customer DROP sended_crm');
        $this->addSql("UPDATE mia_customer SET country_code_cel_phone = '54' WHERE country_code_cel_phone IS NULL");
        $this->addSql('ALTER TABLE mia_customer ALTER country_code_cel_phone SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN mia_customer.verification_code IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BDB4B9E7E FOREIGN KEY (status_sent_crm_id) REFERENCES communication_states_between_platforms (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BD6BF700BD FOREIGN KEY (status_id) REFERENCES customer_status_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9164B3BDB4B9E7E ON mia_customer (status_sent_crm_id)');
        $this->addSql('CREATE INDEX IDX_9164B3BD6BF700BD ON mia_customer (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BDB4B9E7E');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BD6BF700BD');
        $this->addSql('DROP INDEX IDX_9164B3BDB4B9E7E');
        $this->addSql('DROP INDEX IDX_9164B3BD6BF700BD');
        $this->addSql('ALTER TABLE mia_customer ADD status BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD sended_crm BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE mia_customer DROP status_sent_crm_id');
        $this->addSql('ALTER TABLE mia_customer DROP status_id');
        $this->addSql('ALTER TABLE mia_customer DROP attempts_send_crm');
        $this->addSql('ALTER TABLE mia_customer DROP error_message_crm');
        $this->addSql('ALTER TABLE mia_customer DROP verification_code');
        $this->addSql('ALTER TABLE mia_customer DROP change_password');
        $this->addSql('ALTER TABLE mia_customer DROP change_password_date');
        $this->addSql('ALTER TABLE mia_customer ALTER country_code_cel_phone DROP NOT NULL');
    }
}
