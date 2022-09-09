<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220909062715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_clients DROP CONSTRAINT fk_edf65a13be0083cc');
        $this->addSql('DROP INDEX idx_edf65a13be0083cc');
        $this->addSql('ALTER TABLE api_clients RENAME COLUMN apli_client_type_role_id TO api_client_type_role_id');
        $this->addSql('ALTER TABLE api_clients ADD CONSTRAINT FK_EDF65A13E05A9F99 FOREIGN KEY (api_client_type_role_id) REFERENCES api_clients_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_EDF65A13E05A9F99 ON api_clients (api_client_type_role_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_clients DROP CONSTRAINT FK_EDF65A13E05A9F99');
        $this->addSql('DROP INDEX IDX_EDF65A13E05A9F99');
        $this->addSql('ALTER TABLE api_clients RENAME COLUMN api_client_type_role_id TO apli_client_type_role_id');
        $this->addSql('ALTER TABLE api_clients ADD CONSTRAINT fk_edf65a13be0083cc FOREIGN KEY (apli_client_type_role_id) REFERENCES api_clients_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_edf65a13be0083cc ON api_clients (apli_client_type_role_id)');
    }
}
