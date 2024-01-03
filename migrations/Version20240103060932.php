<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103060932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD payment_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEDC058279 FOREIGN KEY (payment_type_id) REFERENCES payment_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEEDC058279 ON orders (payment_type_id)');
        $this->addSql("UPDATE payment_type set name='Transferencia bancaria' WHERE id=1");
        $this->addSql("UPDATE payment_type set name='Pago con Cardnet en 1 cuota' WHERE id=2");
        $this->addSql("UPDATE payment_type set name='Pago con Cardnet en cuotas' WHERE id=3");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEDC058279');
        $this->addSql('DROP INDEX IDX_E52FFDEEDC058279');
        $this->addSql('ALTER TABLE orders DROP payment_type_id');
    }
}
