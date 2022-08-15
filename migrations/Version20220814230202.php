<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220814230202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_addresses ADD country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT FK_C4378D0CF92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT FK_C4378D0C5D83CC1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE customer_addresses ADD CONSTRAINT FK_C4378D0C8BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C4378D0CF92F3E70 ON customer_addresses (country_id)');
        $this->addSql('CREATE INDEX IDX_C4378D0C5D83CC1 ON customer_addresses (state_id)');
        $this->addSql('CREATE INDEX IDX_C4378D0C8BAC62AF ON customer_addresses (city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT FK_C4378D0CF92F3E70');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT FK_C4378D0C5D83CC1');
        $this->addSql('ALTER TABLE customer_addresses DROP CONSTRAINT FK_C4378D0C8BAC62AF');
        $this->addSql('DROP INDEX IDX_C4378D0CF92F3E70');
        $this->addSql('DROP INDEX IDX_C4378D0C5D83CC1');
        $this->addSql('DROP INDEX IDX_C4378D0C8BAC62AF');
        $this->addSql('ALTER TABLE customer_addresses DROP country_id');
        $this->addSql('ALTER TABLE customer_addresses DROP state_id');
        $this->addSql('ALTER TABLE customer_addresses DROP city_id');
    }
}
