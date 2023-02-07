<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207040334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE historical_price_cost_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE historical_price_cost (id INT NOT NULL, product_id BIGINT NOT NULL, created_by_user_id BIGINT NOT NULL, cost DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_73EBD2B34584665A ON historical_price_cost (product_id)');
        $this->addSql('CREATE INDEX IDX_73EBD2B37D182D95 ON historical_price_cost (created_by_user_id)');
        $this->addSql('ALTER TABLE historical_price_cost ADD CONSTRAINT FK_73EBD2B34584665A FOREIGN KEY (product_id) REFERENCES mia_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE historical_price_cost ADD CONSTRAINT FK_73EBD2B37D182D95 FOREIGN KEY (created_by_user_id) REFERENCES mia_user (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $products = $this->connection->executeQuery('SELECT id, cost, price FROM mia_product')->fetchAllAssociative();
        $i = 0;
        foreach ($products as $product) {
            $i++;
            $this->addSql(sprintf('INSERT INTO historical_price_cost(id,product_id, cost, price,created_by_user_id) VALUES (%d,%d, %f, %f, %d)', $i, $product['id'], $product['cost'], $product['price'], 1));
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE historical_price_cost_id_seq CASCADE');
        $this->addSql('ALTER TABLE historical_price_cost DROP CONSTRAINT FK_73EBD2B34584665A');
        $this->addSql('ALTER TABLE historical_price_cost DROP CONSTRAINT FK_73EBD2B37D182D95');
        $this->addSql('DROP TABLE historical_price_cost');
    }
}
