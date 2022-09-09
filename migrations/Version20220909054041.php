<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220909054041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE api_clients_types_roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE api_clients_types_roles (id INT NOT NULL, role VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE api_clients ADD apli_client_type_role_id INT NOT NULL');
        $this->addSql('ALTER TABLE api_clients ADD CONSTRAINT FK_EDF65A13BE0083CC FOREIGN KEY (apli_client_type_role_id) REFERENCES api_clients_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EDF65A13BE0083CC ON api_clients (apli_client_type_role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_clients DROP CONSTRAINT FK_EDF65A13BE0083CC');
        $this->addSql('DROP SEQUENCE api_clients_types_roles_id_seq CASCADE');
        $this->addSql('DROP TABLE api_clients_types_roles');
        $this->addSql('DROP INDEX IDX_EDF65A13BE0083CC');
        $this->addSql('ALTER TABLE api_clients DROP apli_client_type_role_id');
    }
}
