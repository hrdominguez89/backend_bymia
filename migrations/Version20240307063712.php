<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307063712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD total_product_discount_usd DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD promotional_code_discount_usd DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD tax_usd DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD total_order_usd DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders RENAME COLUMN total_product_discount TO total_product_discount_rd');
        $this->addSql('ALTER TABLE orders RENAME COLUMN promotional_code_discount TO promotional_code_discount_rd');
        $this->addSql('ALTER TABLE orders RENAME COLUMN tax TO tax_rd');
        $this->addSql('ALTER TABLE orders RENAME COLUMN total_order TO total_order_rd');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP total_product_discount_usd');
        $this->addSql('ALTER TABLE orders DROP promotional_code_discount_usd');
        $this->addSql('ALTER TABLE orders DROP tax_usd');
        $this->addSql('ALTER TABLE orders DROP total_order_usd');
        $this->addSql('ALTER TABLE orders RENAME COLUMN total_product_discount_rd TO total_product_discount');
        $this->addSql('ALTER TABLE orders RENAME COLUMN promotional_code_discount_rd TO promotional_code_discount');
        $this->addSql('ALTER TABLE orders RENAME COLUMN tax_rd TO tax');
        $this->addSql('ALTER TABLE orders RENAME COLUMN total_order_rd TO total_order');

    }
}
