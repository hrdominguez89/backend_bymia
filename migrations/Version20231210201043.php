<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210201043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdeee92f8f78');
        $this->addSql('DROP SEQUENCE recipients_id_seq CASCADE');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c49395c3f3');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c4f92f3e70');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c45d83cc1');
        $this->addSql('ALTER TABLE recipients DROP CONSTRAINT fk_146632c48bac62af');
        $this->addSql('DROP INDEX idx_e52ffdeee92f8f78');
        $this->addSql('ALTER TABLE orders DROP recipient_id');
        $this->addSql('DROP TABLE recipients');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE recipients_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE recipients (id INT NOT NULL, customer_id BIGINT NOT NULL, country_id INT NOT NULL, state_id INT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, identity_type VARCHAR(50) NOT NULL, identity_number VARCHAR(255) NOT NULL, address TEXT NOT NULL, zip_code VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, additional_info TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_146632c48bac62af ON recipients (city_id)');
        $this->addSql('CREATE INDEX idx_146632c45d83cc1 ON recipients (state_id)');
        $this->addSql('CREATE INDEX idx_146632c4f92f3e70 ON recipients (country_id)');
        $this->addSql('CREATE INDEX idx_146632c49395c3f3 ON recipients (customer_id)');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c49395c3f3 FOREIGN KEY (customer_id) REFERENCES mia_customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c4f92f3e70 FOREIGN KEY (country_id) REFERENCES countries (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c45d83cc1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE recipients ADD CONSTRAINT fk_146632c48bac62af FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD recipient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdeee92f8f78 FOREIGN KEY (recipient_id) REFERENCES recipients (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdeee92f8f78 ON orders (recipient_id)');
    }
}
