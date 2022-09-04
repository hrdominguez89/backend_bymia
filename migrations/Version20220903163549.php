<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903163549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT fk_540dea3743694d09');
        $this->addSql('DROP INDEX idx_540dea3743694d09');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN contidion_id TO condition_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37887793B6 FOREIGN KEY (condition_id) REFERENCES product_condition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA37887793B6 ON mia_product (condition_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37887793B6');
        $this->addSql('DROP INDEX IDX_540DEA37887793B6');
        $this->addSql('ALTER TABLE mia_product RENAME COLUMN condition_id TO contidion_id');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT fk_540dea3743694d09 FOREIGN KEY (contidion_id) REFERENCES product_condition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_540dea3743694d09 ON mia_product (contidion_id)');
    }
}
