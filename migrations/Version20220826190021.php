<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826190021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE countries ADD region_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE countries ADD subregion_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE countries ADD CONSTRAINT FK_5D66EBADDE5EFD14 FOREIGN KEY (region_type_id) REFERENCES region_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE countries ADD CONSTRAINT FK_5D66EBADBEA37AE4 FOREIGN KEY (subregion_type_id) REFERENCES subregion_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5D66EBADDE5EFD14 ON countries (region_type_id)');
        $this->addSql('CREATE INDEX IDX_5D66EBADBEA37AE4 ON countries (subregion_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE countries DROP CONSTRAINT FK_5D66EBADDE5EFD14');
        $this->addSql('ALTER TABLE countries DROP CONSTRAINT FK_5D66EBADBEA37AE4');
        $this->addSql('DROP INDEX IDX_5D66EBADDE5EFD14');
        $this->addSql('DROP INDEX IDX_5D66EBADBEA37AE4');
        $this->addSql('ALTER TABLE countries DROP region_type_id');
        $this->addSql('ALTER TABLE countries DROP subregion_type_id');
    }
}
