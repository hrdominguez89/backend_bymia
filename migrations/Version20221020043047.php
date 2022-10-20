<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020043047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE mia_sub_category SET visible = False WHERE visible is null');
        $this->addSql('UPDATE mia_category SET visible = False WHERE visible is null');
        $this->addSql('UPDATE mia_category SET created_at = CURRENT_TIMESTAMP WHERE created_at is null');
        $this->addSql('UPDATE mia_sub_category SET visible = False WHERE visible is null');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
