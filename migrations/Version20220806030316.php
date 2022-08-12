<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220806030316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE customers_types_roles_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customers_types_roles (id INT NOT NULL, role VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE mia_customer ADD customer_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_customer ADD CONSTRAINT FK_9164B3BDD991282D FOREIGN KEY (customer_type_id) REFERENCES customers_types_roles (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9164B3BDD991282D ON mia_customer (customer_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_customer DROP CONSTRAINT FK_9164B3BDD991282D');
        $this->addSql('DROP SEQUENCE customers_types_roles_id_seq CASCADE');
        $this->addSql('DROP TABLE customers_types_roles');
        $this->addSql('DROP INDEX IDX_9164B3BDD991282D');
        $this->addSql('ALTER TABLE mia_customer DROP customer_type_id');
    }
}
