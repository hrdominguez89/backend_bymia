<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226083901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sections_home ADD tag_section1_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE sections_home ADD tag_section2_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE sections_home ADD tag_section3_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE sections_home ADD tag_section4_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE27AADECB2 FOREIGN KEY (tag_section1_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE26818435C FOREIGN KEY (tag_section2_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2D0A42439 FOREIGN KEY (tag_section3_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE24D731C80 FOREIGN KEY (tag_section4_id) REFERENCES mia_tag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBCADEE27AADECB2 ON sections_home (tag_section1_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE26818435C ON sections_home (tag_section2_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE2D0A42439 ON sections_home (tag_section3_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE24D731C80 ON sections_home (tag_section4_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE27AADECB2');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE26818435C');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2D0A42439');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE24D731C80');
        $this->addSql('DROP INDEX IDX_CBCADEE27AADECB2');
        $this->addSql('DROP INDEX IDX_CBCADEE26818435C');
        $this->addSql('DROP INDEX IDX_CBCADEE2D0A42439');
        $this->addSql('DROP INDEX IDX_CBCADEE24D731C80');
        $this->addSql('ALTER TABLE sections_home DROP tag_section1_id');
        $this->addSql('ALTER TABLE sections_home DROP tag_section2_id');
        $this->addSql('ALTER TABLE sections_home DROP tag_section3_id');
        $this->addSql('ALTER TABLE sections_home DROP tag_section4_id');
    }
}
