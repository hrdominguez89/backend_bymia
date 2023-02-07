<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207090227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ALTER reviews SET DEFAULT floor(random() * 100 + 1)');
        $this->addSql('ALTER TABLE product_discount ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE product_discount ALTER used SET DEFAULT 0');
        $this->addSql('ALTER TABLE product_discount ALTER active SET DEFAULT true');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE product_discount ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE product_discount ALTER used DROP DEFAULT');
        $this->addSql('ALTER TABLE product_discount ALTER active DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER reviews SET DEFAULT floor(((random() * (100)::double precision) + (1)::double precision))');
    }
}
