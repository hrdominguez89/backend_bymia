<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020060925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_clients ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE cities ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('UPDATE cities SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        $this->addSql('ALTER TABLE cities ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE countries ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('UPDATE countries SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        $this->addSql('ALTER TABLE countries ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE mia_category DROP updated_at');
        $this->addSql('ALTER TABLE mia_category ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('UPDATE mia_category SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        $this->addSql('ALTER TABLE mia_category ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE mia_category ALTER visible SET NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('UPDATE mia_product SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        $this->addSql('ALTER TABLE mia_product ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE mia_tag DROP api_id');
        $this->addSql('ALTER TABLE mia_tag ALTER created_at SET DEFAULT CURRENT_TIMESTAMP');
        $this->addSql('UPDATE mia_tag SET created_at = CURRENT_TIMESTAMP WHERE created_at IS NULL');
        $this->addSql('ALTER TABLE mia_tag ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE warehouses ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE api_clients ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE countries ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE countries ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_product ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_product ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_tag ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_tag ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_tag ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE warehouses DROP created_at');
        $this->addSql('ALTER TABLE mia_category ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_category ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE mia_category ALTER created_at DROP NOT NULL');
        $this->addSql('ALTER TABLE mia_category ALTER visible DROP NOT NULL');
        $this->addSql('ALTER TABLE cities ALTER created_at DROP DEFAULT');
        $this->addSql('ALTER TABLE cities ALTER created_at DROP NOT NULL');
    }
}
