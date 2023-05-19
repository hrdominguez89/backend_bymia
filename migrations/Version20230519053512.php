<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519053512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD attempts_send_crm SMALLINT DEFAULT 0');
        $this->addSql('UPDATE orders SET attempts_send_crm = 1');
        $this->addSql('ALTER TABLE orders ALTER attempts_send_crm SET NOT NULL');
        $this->addSql('ALTER TABLE orders DROP attemps_send_crm');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders ADD attemps_send_crm SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE orders DROP attempts_send_crm');
    }
}
