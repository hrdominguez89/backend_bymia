<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203095505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD receiver_country_id INT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_document_type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_document VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_phone_cell VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_phone_home VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_address TEXT NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_cod_zip VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_additional_info TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEBA8B38B9 FOREIGN KEY (receiver_country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE5D83CC1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE8BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEEBA8B38B9 ON orders (receiver_country_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE5D83CC1 ON orders (state_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE8BAC62AF ON orders (city_id)');
        $this->addSql('ALTER TABLE orders_products ADD discount DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders_products DROP discount');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEBA8B38B9');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE5D83CC1');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE8BAC62AF');
        $this->addSql('DROP INDEX IDX_E52FFDEEBA8B38B9');
        $this->addSql('DROP INDEX IDX_E52FFDEE5D83CC1');
        $this->addSql('DROP INDEX IDX_E52FFDEE8BAC62AF');
        $this->addSql('ALTER TABLE orders DROP receiver_country_id');
        $this->addSql('ALTER TABLE orders DROP state_id');
        $this->addSql('ALTER TABLE orders DROP city_id');
        $this->addSql('ALTER TABLE orders DROP receiver_name');
        $this->addSql('ALTER TABLE orders DROP receiver_document_type');
        $this->addSql('ALTER TABLE orders DROP receiver_document');
        $this->addSql('ALTER TABLE orders DROP receiver_phone_cell');
        $this->addSql('ALTER TABLE orders DROP receiver_phone_home');
        $this->addSql('ALTER TABLE orders DROP receiver_email');
        $this->addSql('ALTER TABLE orders DROP receiver_address');
        $this->addSql('ALTER TABLE orders DROP receiver_cod_zip');
        $this->addSql('ALTER TABLE orders DROP receiver_additional_info');
    }
}
