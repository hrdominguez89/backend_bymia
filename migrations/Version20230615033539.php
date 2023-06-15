<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615033539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE shipping_types_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE shipping_types (id INT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO shipping_types (id,name) VALUES (1,'National shipping'),(2,'International shipping'),(3,'Pickup national'),(4,'pickup internacional')");
        $this->addSql('ALTER TABLE orders ADD shipping_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE23048A57 FOREIGN KEY (shipping_type_id) REFERENCES shipping_types (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEE23048A57 ON orders (shipping_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE23048A57');
        $this->addSql('DROP SEQUENCE shipping_types_id_seq CASCADE');
        $this->addSql('DROP TABLE shipping_types');
        $this->addSql('DROP INDEX IDX_E52FFDEE23048A57');
        $this->addSql('ALTER TABLE orders DROP shipping_type_id');
    }
}
