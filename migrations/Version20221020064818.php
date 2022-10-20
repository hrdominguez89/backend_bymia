<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020064818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD conditium VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER visible SET DEFAULT false');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN upc TO cod');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN id_3pl TO id3pl');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_540DEA37F9038C4 ON mia_product (sku)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_540DEA37F9038C4');
        $this->addSql('ALTER TABLE mia_product DROP conditium');
        $this->addSql('ALTER TABLE mia_product ALTER visible DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN cod TO upc');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN id3pl TO id_3pl');
    }
}
