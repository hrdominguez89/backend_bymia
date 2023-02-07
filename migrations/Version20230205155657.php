<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230205155657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_tag DROP CONSTRAINT fk_e3a6e39c4584665a');
        $this->addSql('ALTER TABLE product_tag DROP CONSTRAINT fk_e3a6e39cbad26311');
        $this->addSql('DROP TABLE product_tag');
        $this->addSql('ALTER TABLE mia_tag DROP id3pl');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE product_tag (product_id BIGINT NOT NULL, tag_id BIGINT NOT NULL, PRIMARY KEY(product_id, tag_id))');
        $this->addSql('CREATE INDEX idx_e3a6e39cbad26311 ON product_tag (tag_id)');
        $this->addSql('CREATE INDEX idx_e3a6e39c4584665a ON product_tag (product_id)');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT fk_e3a6e39c4584665a FOREIGN KEY (product_id) REFERENCES mia_product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_tag ADD CONSTRAINT fk_e3a6e39cbad26311 FOREIGN KEY (tag_id) REFERENCES mia_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_tag ADD id3pl INT DEFAULT NULL');
    }
}
