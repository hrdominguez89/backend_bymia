<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207073606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ALTER reviews SET DEFAULT floor(random() * 100 + 1)');
        $this->addSql('ALTER TABLE orders_products ADD product_discount_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders_products ADD CONSTRAINT FK_749C879CE053FF00 FOREIGN KEY (product_discount_id) REFERENCES product_discount (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_749C879CE053FF00 ON orders_products (product_discount_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product ALTER reviews SET DEFAULT floor(((random() * (100)::double precision) + (1)::double precision))');
        $this->addSql('ALTER TABLE orders_products DROP CONSTRAINT FK_749C879CE053FF00');
        $this->addSql('DROP INDEX IDX_749C879CE053FF00');
        $this->addSql('ALTER TABLE orders_products DROP product_discount_id');
    }
}
