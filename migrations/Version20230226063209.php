<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226063209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sections_home_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sections_home (id INT NOT NULL, category1_section1_id BIGINT NOT NULL, category2_section1_id BIGINT NOT NULL, category3_section1_id BIGINT NOT NULL, category1_section2_id BIGINT NOT NULL, category2_section2_id BIGINT NOT NULL, category3_section2_id BIGINT NOT NULL, category1_section3_id BIGINT NOT NULL, category2_section3_id BIGINT NOT NULL, category3_section3_id BIGINT NOT NULL, category1_section4_id BIGINT DEFAULT NULL, category2_section4_id BIGINT NOT NULL, category3_section4_id BIGINT NOT NULL, title_section1 VARCHAR(255) NOT NULL, title_section2 VARCHAR(255) NOT NULL, title_section3 VARCHAR(255) NOT NULL, title_section4 VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CBCADEE2161BD1D0 ON sections_home (category1_section1_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE2ABD1BD1E ON sections_home (category2_section1_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE27647649B ON sections_home (category3_section1_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE24AE7E3E ON sections_home (category1_section2_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE2B96412F0 ON sections_home (category2_section2_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE264F2CB75 ON sections_home (category3_section2_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE2BC12195B ON sections_home (category1_section3_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE21D87595 ON sections_home (category2_section3_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE2DC4EAC10 ON sections_home (category3_section3_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE221C521E2 ON sections_home (category1_section4_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE29C0F4D2C ON sections_home (category2_section4_id)');
        $this->addSql('CREATE INDEX IDX_CBCADEE2419994A9 ON sections_home (category3_section4_id)');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2161BD1D0 FOREIGN KEY (category1_section1_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2ABD1BD1E FOREIGN KEY (category2_section1_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE27647649B FOREIGN KEY (category3_section1_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE24AE7E3E FOREIGN KEY (category1_section2_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2B96412F0 FOREIGN KEY (category2_section2_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE264F2CB75 FOREIGN KEY (category3_section2_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2BC12195B FOREIGN KEY (category1_section3_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE21D87595 FOREIGN KEY (category2_section3_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2DC4EAC10 FOREIGN KEY (category3_section3_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE221C521E2 FOREIGN KEY (category1_section4_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE29C0F4D2C FOREIGN KEY (category2_section4_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sections_home ADD CONSTRAINT FK_CBCADEE2419994A9 FOREIGN KEY (category3_section4_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE sections_home_id_seq CASCADE');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2161BD1D0');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2ABD1BD1E');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE27647649B');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE24AE7E3E');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2B96412F0');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE264F2CB75');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2BC12195B');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE21D87595');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2DC4EAC10');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE221C521E2');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE29C0F4D2C');
        $this->addSql('ALTER TABLE sections_home DROP CONSTRAINT FK_CBCADEE2419994A9');
        $this->addSql('DROP TABLE sections_home');
    }
}
