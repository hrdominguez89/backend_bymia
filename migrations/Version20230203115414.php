<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203115414 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD warehouse_id INT');
        $this->addSql('UPDATE orders SET warehouse_id = 2 WHERE warehouse_id IS NULL');
        $this->addSql('ALTER TABLE orders ALTER COLUMN warehouse_id SET NOT NULL');
        $this->addSql('ALTER TABLE orders RENAME COLUMN product_discount TO total_product_discount');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEE5080ECDE ON orders (warehouse_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE5080ECDE');
        $this->addSql('DROP INDEX IDX_E52FFDEE5080ECDE');
        $this->addSql('ALTER TABLE orders DROP warehouse_id');
        $this->addSql('ALTER TABLE orders RENAME COLUMN total_product_discount TO product_discount');
    }
}
