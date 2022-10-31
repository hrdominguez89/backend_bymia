<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028153657 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_brand ALTER nomenclature TYPE VARCHAR(3)');
        $this->addSql('ALTER TABLE mia_category ADD principal BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_161124755E237E06 ON mia_category (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16112475799A3652 ON mia_category (nomenclature)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10E26BEB5E237E06 ON mia_sub_category (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_brand ALTER nomenclature TYPE VARCHAR(2)');
        $this->addSql('DROP INDEX UNIQ_10E26BEB5E237E06');
        $this->addSql('DROP INDEX UNIQ_161124755E237E06');
        $this->addSql('DROP INDEX UNIQ_16112475799A3652');
        $this->addSql('ALTER TABLE mia_category DROP principal');
    }
}
