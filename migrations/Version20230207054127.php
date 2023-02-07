<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207054127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $lastHistoricalPriceCostId = $this->connection->executeQuery('SELECT id FROM historical_price_cost ORDER BY id DESC LIMIT 1')->fetchAllAssociative();
        $id = ((int)$lastHistoricalPriceCostId[0]['id']) + 1;
        $this->addSql(sprintf('ALTER SEQUENCE historical_price_cost_id_seq RESTART WITH %d',$id));
        // this up() migration is auto-generated, please modify it to your needs

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
