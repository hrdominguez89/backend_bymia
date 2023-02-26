<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230226214747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE brands_sections_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE brands_sections (id INT NOT NULL, brand_name1 VARCHAR(255) NOT NULL, brand_image1 VARCHAR(255) NOT NULL, brand_name2 VARCHAR(255) NOT NULL, brand_image2 VARCHAR(255) NOT NULL, brand_name3 VARCHAR(255) NOT NULL, brand_image3 VARCHAR(255) NOT NULL, brand_name4 VARCHAR(255) NOT NULL, brand_image4 VARCHAR(255) NOT NULL, brand_name5 VARCHAR(255) NOT NULL, brand_image5 VARCHAR(255) NOT NULL, brand_name6 VARCHAR(255) NOT NULL, brand_image6 VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE brands_sections_id_seq CASCADE');
        $this->addSql('DROP TABLE brands_sections');
    }
}
