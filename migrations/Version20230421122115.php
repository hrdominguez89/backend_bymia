<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230421122115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders_products ADD shopping_cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879C45F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_749C879C45F80CD ON orders_products (shopping_cart_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879C45F80CD');
        $this->addSql('DROP INDEX UNIQ_749C879C45F80CD');
        $this->addSql('ALTER TABLE orders_products DROP shopping_cart_id');
    }
}
