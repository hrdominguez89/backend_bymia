<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211016031032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE home_image_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE home_image (id INT NOT NULL, order_image INT NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
//        $this->addSql('ALTER TABLE mia_reset_password_request ADD selector VARCHAR(20) NOT NULL');
//        $this->addSql('ALTER TABLE mia_reset_password_request ADD hashed_token VARCHAR(100) NOT NULL');
//        $this->addSql('ALTER TABLE mia_reset_password_request ADD requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
//        $this->addSql('ALTER TABLE mia_reset_password_request ADD expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
//        $this->addSql('COMMENT ON COLUMN mia_reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
//        $this->addSql('COMMENT ON COLUMN mia_reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE home_image_id_seq CASCADE');
        $this->addSql('DROP TABLE home_image');
//        $this->addSql('ALTER TABLE mia_reset_password_request DROP selector');
//        $this->addSql('ALTER TABLE mia_reset_password_request DROP hashed_token');
//        $this->addSql('ALTER TABLE mia_reset_password_request DROP requested_at');
//        $this->addSql('ALTER TABLE mia_reset_password_request DROP expires_at');
    }
}
