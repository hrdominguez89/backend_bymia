<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801034900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE roles (id INT NOT NULL, role VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE mia_user ADD role_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_user ADD lastname VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_user ADD CONSTRAINT FK_E5DEEE85D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E5DEEE85D60322AC ON mia_user (role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_user DROP CONSTRAINT FK_E5DEEE85D60322AC');
        $this->addSql('DROP SEQUENCE roles_id_seq CASCADE');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP INDEX IDX_E5DEEE85D60322AC');
        $this->addSql('ALTER TABLE mia_user DROP role_id');
        $this->addSql('ALTER TABLE mia_user DROP lastname');
    }
}
