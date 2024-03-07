<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306234155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_790bb58b5e237e06');
        $this->addSql('ALTER TABLE mia_currency ADD sign VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE mia_currency DROP api_id');
        $this->addSql('ALTER TABLE mia_currency DROP abbreviation');
        $this->addSql('ALTER TABLE mia_currency DROP main');
        $this->addSql("INSERT INTO mia_currency (id,name,sign) values(1,'Peso Dominicano','$')");
        $this->addSql("INSERT INTO mia_currency (id,name,sign) values(2,'Dolar','U\$D')");
        $this->addSql('UPDATE mia_product set currency_id = 1');
        $this->addSql('ALTER TABLE mia_product ALTER currency_id SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_currency ADD api_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_currency ADD abbreviation VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_currency ADD main BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE mia_currency DROP sign');
        $this->addSql('CREATE UNIQUE INDEX uniq_790bb58b5e237e06 ON mia_currency (name)');
        $this->addSql('ALTER TABLE mia_product ALTER currency_id DROP NOT NULL');
    }
}
