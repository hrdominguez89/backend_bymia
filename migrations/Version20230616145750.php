<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230616145750 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections_home ALTER category1_section1_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section1_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section1_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category1_section2_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section2_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section2_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category1_section3_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section3_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section3_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section4_id DROP NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section4_id DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sections_home ALTER category1_section1_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section1_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section1_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category1_section2_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section2_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section2_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category1_section3_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section3_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section3_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category2_section4_id SET NOT NULL');
        $this->addSql('ALTER TABLE sections_home ALTER category3_section4_id SET NOT NULL');
    }
}
