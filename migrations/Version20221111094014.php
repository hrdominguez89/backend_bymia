<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221111094014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand ADD principal BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE mia_category ALTER id3pl TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_category RENAME COLUMN description TO description_es');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_category ALTER id3pl TYPE INT');
        $this->addSql('ALTER TABLE mia_category RENAME COLUMN description_es TO description');
        $this->addSql('ALTER TABLE mia_brand DROP principal');
    }
}
