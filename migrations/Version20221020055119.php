<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020055119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_clients DROP updated_at');
        $this->addSql('ALTER TABLE mia_product DROP updated_at');
        $this->addSql('ALTER TABLE mia_tag DROP updated_at');
        $this->addSql('ALTER TABLE warehouses RENAME COLUMN api_id TO id3pl');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE warehouses RENAME COLUMN id3pl TO api_id');
        $this->addSql('ALTER TABLE mia_tag ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE api_clients ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
    }
}
