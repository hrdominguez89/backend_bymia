<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230123012011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE actions_product_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE history_product_stock_updated_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE actions_product_type (id INT NOT NULL, name VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql("INSERT INTO actions_product_type (id,name) VALUES (1,'CREADO'),(2,'EDITADO'),(3,'PO ABIERTO'),(4,'RECEPCION PO'),(5,'SO CREADA'),(6,'SO ENVIADA')");
        $this->addSql('CREATE TABLE history_product_stock_updated (id INT NOT NULL, product_id BIGINT NOT NULL, action_id INT NOT NULL, onhand INT NOT NULL, commited INT NOT NULL, incomming INT NOT NULL, available INT NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9B02AB2A4584665A ON history_product_stock_updated (product_id)');
        $this->addSql('CREATE INDEX IDX_9B02AB2A9D32F035 ON history_product_stock_updated (action_id)');
        $this->addSql('ALTER TABLE history_product_stock_updated ADD CONSTRAINT FK_9B02AB2A4584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE history_product_stock_updated ADD CONSTRAINT FK_9B02AB2A9D32F035 FOREIGN KEY (action_id) REFERENCES actions_product_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE actions_product_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE history_product_stock_updated_id_seq CASCADE');
        $this->addSql('ALTER TABLE history_product_stock_updated DROP CONSTRAINT FK_9B02AB2A4584665A');
        $this->addSql('ALTER TABLE history_product_stock_updated DROP CONSTRAINT FK_9B02AB2A9D32F035');
        $this->addSql('DROP TABLE actions_product_type');
        $this->addSql('DROP TABLE history_product_stock_updated');
    }
}
