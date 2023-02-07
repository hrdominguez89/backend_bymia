<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207073336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_discount_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product_discount (id INT NOT NULL, product_id BIGINT NOT NULL, created_by_user_id BIGINT NOT NULL, percentage_discount INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, product_limit BIGINT NOT NULL, used BIGINT NOT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2A50DE994584665A ON product_discount (product_id)');
        $this->addSql('CREATE INDEX IDX_2A50DE997D182D95 ON product_discount (created_by_user_id)');
        $this->addSql('ALTER TABLE product_discount ADD CONSTRAINT FK_2A50DE994584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE product_discount ADD CONSTRAINT FK_2A50DE997D182D95 FOREIGN KEY (created_by_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ALTER reviews SET DEFAULT floor(random() * 100 + 1)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_discount_id_seq CASCADE');
        $this->addSql('ALTER TABLE product_discount DROP CONSTRAINT FK_2A50DE994584665A');
        $this->addSql('ALTER TABLE product_discount DROP CONSTRAINT FK_2A50DE997D182D95');
        $this->addSql('DROP TABLE product_discount');
        $this->addSql('ALTER TABLE mia_product ALTER reviews SET DEFAULT floor(((random() * (100)::double precision) + (1)::double precision))');
    }
}
