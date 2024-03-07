<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240307194515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD promotional_code_discount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP promotional_code_discount_rd');
        $this->addSql('ALTER TABLE orders DROP promotional_code_discount_usd');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders ADD promotional_code_discount_usd DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE orders RENAME COLUMN promotional_code_discount TO promotional_code_discount_rd');
    }
}
