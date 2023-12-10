<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231210193201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_addresses ADD name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD identity_type VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD identity_number VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD phone VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_addresses ADD email VARCHAR(255) DEFAULT NULL');
        $this->addSql("UPDATE email_types SET name='VALIDÁ TU CUENTA PARA COMPRAR POR BYMIA' where id = 1");
        $this->addSql("UPDATE email_types SET name='YA PODÉS COMPRAR EN BYMIA' where id = 2");
        $this->addSql("UPDATE email_types SET name='SOLICITUD DE CAMBIO DE CONTRASEÑA' where id = 3");
        $this->addSql("UPDATE email_types SET name='SOLICITUD DE CAMBIO DE CONTRASEÑA' where id = 4");
        $this->addSql("UPDATE email_types SET name='CAMBIO DE CONTRASEÑA EXITOSO' where id = 5");
        $this->addSql("UPDATE email_types SET name='CONTACTANOS' where id = 6");
        $this->addSql("UPDATE email_types SET name='PRECIO DE LISTA' where id = 7");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE customer_addresses DROP name');
        $this->addSql('ALTER TABLE customer_addresses DROP identity_type');
        $this->addSql('ALTER TABLE customer_addresses DROP identity_number');
        $this->addSql('ALTER TABLE customer_addresses DROP phone');
        $this->addSql('ALTER TABLE customer_addresses DROP email');
    }
}
