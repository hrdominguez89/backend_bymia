<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203100123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee5d83cc1');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT fk_e52ffdee8bac62af');
        $this->addSql('DROP INDEX idx_e52ffdee8bac62af');
        $this->addSql('DROP INDEX idx_e52ffdee5d83cc1');
        $this->addSql('ALTER TABLE orders ADD receiver_state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD receiver_city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP state_id');
        $this->addSql('ALTER TABLE orders DROP city_id');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3D7DC734 FOREIGN KEY (receiver_state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEF695530D FOREIGN KEY (receiver_city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_E52FFDEE3D7DC734 ON orders (receiver_state_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEF695530D ON orders (receiver_city_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEE3D7DC734');
        $this->addSql('ALTER TABLE orders DROP CONSTRAINT FK_E52FFDEEF695530D');
        $this->addSql('DROP INDEX IDX_E52FFDEE3D7DC734');
        $this->addSql('DROP INDEX IDX_E52FFDEEF695530D');
        $this->addSql('ALTER TABLE orders ADD state_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP receiver_state_id');
        $this->addSql('ALTER TABLE orders DROP receiver_city_id');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee5d83cc1 FOREIGN KEY (state_id) REFERENCES states (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT fk_e52ffdee8bac62af FOREIGN KEY (city_id) REFERENCES cities (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_e52ffdee8bac62af ON orders (city_id)');
        $this->addSql('CREATE INDEX idx_e52ffdee5d83cc1 ON orders (state_id)');
    }
}
