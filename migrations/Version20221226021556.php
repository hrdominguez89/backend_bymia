<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221226021556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_types ADD template_name VARCHAR(255)');
        $this->addSql("UPDATE email_types SET template_name='validation' WHERE id=1");
        $this->addSql("UPDATE email_types SET template_name='welcome' WHERE id=2");
        $this->addSql("UPDATE email_types SET template_name='forget.password' WHERE id=3");
        $this->addSql("UPDATE email_types SET template_name='password.change.request' WHERE id=4");
        $this->addSql("UPDATE email_types SET template_name='password.change.successful' WHERE id=5");
        $this->addSql('ALTER TABLE email_types ALTER COLUMN template_name SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE email_types DROP template_name');
    }
}
