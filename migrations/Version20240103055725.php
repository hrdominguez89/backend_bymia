<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103055725 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE payment_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE payment_type (id INT NOT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO payment_type (id,name) VALUES (1,'Transaction'),(2,'Pay in 1 payment'),(3,'Pay in several payments')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE payment_type_id_seq CASCADE');
        $this->addSql('DROP TABLE payment_type');
    }
}
