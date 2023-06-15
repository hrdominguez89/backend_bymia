<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613220524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE recipients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE recipients (id INT NOT NULL, customer_id BIGINT NOT NULL, country_id INT NOT NULL, state_id INT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, identity_type VARCHAR(50) NOT NULL, identity_number VARCHAR(255) NOT NULL, address TEXT NOT NULL, zip_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, additional_info TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_146632C49395C3F3 ON recipients (customer_id)');
        $this->addSql('CREATE INDEX IDX_146632C4F92F3E70 ON recipients (country_id)');
        $this->addSql('CREATE INDEX IDX_146632C45D83CC1 ON recipients (state_id)');
        $this->addSql('CREATE INDEX IDX_146632C48BAC62AF ON recipients (city_id)');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C49395C3F3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C4F92F3E70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C45D83CC1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT FK_146632C48BAC62AF FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD recipient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEE92F8F78 FOREIGN KEY (recipient_id) REFERENCES recipients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEEE92F8F78 ON orders (recipient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEE92F8F78');
        $this->addSql('DROP SEQUENCE recipients_id_seq CASCADE');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C49395C3F3');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C4F92F3E70');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C45D83CC1');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT FK_146632C48BAC62AF');
        $this->addSql('DROP TABLE recipients');
        $this->addSql('DROP INDEX IDX_E52FFDEEE92F8F78');
        $this->addSql('ALTER TABLE orders DROP recipient_id');
    }
}
