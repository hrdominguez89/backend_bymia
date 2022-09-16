<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220916024555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE faqs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE faqs (id INT NOT NULL, topic_id INT NOT NULL, question VARCHAR(255) NOT NULL, answer TEXT NOT NULL, icon VARCHAR(255) DEFAULT NULL, visible BOOLEAN NOT NULL, number_order SMALLINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8934BEE51F55203D ON faqs (topic_id)');
        $this->addSql('ALTER TABLE faqs ADD CONSTRAINT FK_8934BEE51F55203D FOREIGN KEY (topic_id) REFERENCES topics (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE faqs_id_seq CASCADE');
        $this->addSql('ALTER TABLE faqs DROP CONSTRAINT FK_8934BEE51F55203D');
        $this->addSql('DROP TABLE faqs');
    }
}
