<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230304200530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE status_type_favorite_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE status_type_favorite (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO status_type_favorite (id,name) VALUES (1,'Activo')");
        $this->addSql("INSERT INTO status_type_favorite (id,name) VALUES (2,'Eliminado')");
        $this->addSql("INSERT INTO status_type_favorite (id,name) VALUES (3,'En carrito')");
        $this->addSql('ALTER TABLE mia_favorite_product ADD status_id INT NOT NULL');
        $this->addSql('ALTER TABLE mia_favorite_product ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE mia_favorite_product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_favorite_product ADD CONSTRAINT FK_C5E63AA76BF700BD FOREIGN KEY (status_id) REFERENCES status_type_favorite (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C5E63AA76BF700BD ON mia_favorite_product (status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_favorite_product DROP CONSTRAINT FK_C5E63AA76BF700BD');
        $this->addSql('DROP SEQUENCE status_type_favorite_id_seq CASCADE');
        $this->addSql('DROP TABLE status_type_favorite');
        $this->addSql('DROP INDEX IDX_C5E63AA76BF700BD');
        $this->addSql('ALTER TABLE mia_favorite_product DROP status_id');
        $this->addSql('ALTER TABLE mia_favorite_product DROP created_at');
        $this->addSql('ALTER TABLE mia_favorite_product DROP updated_at');
    }
}
