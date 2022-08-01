<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220801022914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE mia_specification_type_id_seq CASCADE');
        $this->addSql('DROP TABLE mia_specification_type');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE mia_specification_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE mia_specification_type (id BIGINT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL, default_custom_fields_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }
}
