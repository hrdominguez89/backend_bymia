<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230613000346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD sales_id_3pl INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ALTER biil_country_id DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_country_id DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER customer_identity_type DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER customer_identity_number DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER international_shipping DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER shipping DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER bill_address_order DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER bill_postal_code DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER subtotal DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER total_product_discount DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER promotional_code_discount DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER tax DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER shipping_cost DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER shipping_discount DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER paypal_service_cost DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER total_order DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_name DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_document_type DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_document DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_phone_cell DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_email DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_address DROP NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_cod_zip DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE orders DROP sales_id_3pl');
        $this->addSql('ALTER TABLE orders ALTER biil_country_id SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_country_id SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER customer_identity_type SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER customer_identity_number SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER international_shipping SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER shipping SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER bill_address_order SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER bill_postal_code SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER subtotal SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER total_product_discount SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER promotional_code_discount SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER tax SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER shipping_cost SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER shipping_discount SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER paypal_service_cost SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER total_order SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_name SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_document_type SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_document SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_phone_cell SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_email SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_address SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER receiver_cod_zip SET NOT NULL');
    }
}
