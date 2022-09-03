<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220903062758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD warehouse_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD category_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD subcategory_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD brand_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD contidion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD status_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA375080ECDE FOREIGN KEY (warehouse_id) REFERENCES warehouses (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3712469DE2 FOREIGN KEY (category_id) REFERENCES mia_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA375DC6FE57 FOREIGN KEY (subcategory_id) REFERENCES mia_sub_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3744F5D008 FOREIGN KEY (brand_id) REFERENCES mia_brand (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3743694D09 FOREIGN KEY (contidion_id) REFERENCES product_condition (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37CD9CFB16 FOREIGN KEY (status_type_id) REFERENCES product_status_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA375080ECDE ON mia_product (warehouse_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3712469DE2 ON mia_product (category_id)');
        $this->addSql('CREATE INDEX IDX_540DEA375DC6FE57 ON mia_product (subcategory_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3744F5D008 ON mia_product (brand_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3743694D09 ON mia_product (contidion_id)');
        $this->addSql('CREATE INDEX IDX_540DEA37CD9CFB16 ON mia_product (status_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA375080ECDE');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3712469DE2');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA375DC6FE57');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3744F5D008');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3743694D09');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37CD9CFB16');
        $this->addSql('DROP INDEX IDX_540DEA375080ECDE');
        $this->addSql('DROP INDEX IDX_540DEA3712469DE2');
        $this->addSql('DROP INDEX IDX_540DEA375DC6FE57');
        $this->addSql('DROP INDEX IDX_540DEA3744F5D008');
        $this->addSql('DROP INDEX IDX_540DEA3743694D09');
        $this->addSql('DROP INDEX IDX_540DEA37CD9CFB16');
        $this->addSql('ALTER TABLE mia_product DROP warehouse_id');
        $this->addSql('ALTER TABLE mia_product DROP category_id');
        $this->addSql('ALTER TABLE mia_product DROP subcategory_id');
        $this->addSql('ALTER TABLE mia_product DROP brand_id');
        $this->addSql('ALTER TABLE mia_product DROP contidion_id');
        $this->addSql('ALTER TABLE mia_product DROP status_type_id');
    }
}
