<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031033831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_category ALTER id3pl TYPE INT');
        $this->addSql('ALTER TABLE mia_sub_category ALTER id3pl TYPE INT');
        $this->addSql('ALTER TABLE mia_tag ADD id3pl INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_sub_category ALTER id3pl TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_category ALTER id3pl TYPE BIGINT');
        $this->addSql('ALTER TABLE mia_tag DROP id3pl');
    }
}
