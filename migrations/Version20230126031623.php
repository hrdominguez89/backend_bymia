<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230126031623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mia_product ADD model_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD color_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD screen_resolution_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD screen_size_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD cpu_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD gpu_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD memory_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD storage_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD op_sys_id BIGINT DEFAULT NULL');
        $this->addSql('ALTER TABLE mia_product ADD conditium_id BIGINT NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD weight DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA377975B7E7 FOREIGN KEY (model_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA377ADA1FB5 FOREIGN KEY (color_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA376C8C2577 FOREIGN KEY (screen_resolution_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37E6F67FE9 FOREIGN KEY (screen_size_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA373917014 FOREIGN KEY (cpu_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3798003202 FOREIGN KEY (gpu_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37CCC80CB3 FOREIGN KEY (memory_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA375CC5DB90 FOREIGN KEY (storage_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA3785E0BC94 FOREIGN KEY (op_sys_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mia_product ADD CONSTRAINT FK_540DEA37B092B37B FOREIGN KEY (conditium_id) REFERENCES mia_specification (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_540DEA377975B7E7 ON mia_product (model_id)');
        $this->addSql('CREATE INDEX IDX_540DEA377ADA1FB5 ON mia_product (color_id)');
        $this->addSql('CREATE INDEX IDX_540DEA376C8C2577 ON mia_product (screen_resolution_id)');
        $this->addSql('CREATE INDEX IDX_540DEA37E6F67FE9 ON mia_product (screen_size_id)');
        $this->addSql('CREATE INDEX IDX_540DEA373917014 ON mia_product (cpu_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3798003202 ON mia_product (gpu_id)');
        $this->addSql('CREATE INDEX IDX_540DEA37CCC80CB3 ON mia_product (memory_id)');
        $this->addSql('CREATE INDEX IDX_540DEA375CC5DB90 ON mia_product (storage_id)');
        $this->addSql('CREATE INDEX IDX_540DEA3785E0BC94 ON mia_product (op_sys_id)');
        $this->addSql('CREATE INDEX IDX_540DEA37B092B37B ON mia_product (conditium_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA377975B7E7');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA377ADA1FB5');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA376C8C2577');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37E6F67FE9');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA373917014');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3798003202');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37CCC80CB3');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA375CC5DB90');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA3785E0BC94');
        $this->addSql('ALTER TABLE mia_product DROP CONSTRAINT FK_540DEA37B092B37B');
        $this->addSql('DROP INDEX IDX_540DEA377975B7E7');
        $this->addSql('DROP INDEX IDX_540DEA377ADA1FB5');
        $this->addSql('DROP INDEX IDX_540DEA376C8C2577');
        $this->addSql('DROP INDEX IDX_540DEA37E6F67FE9');
        $this->addSql('DROP INDEX IDX_540DEA373917014');
        $this->addSql('DROP INDEX IDX_540DEA3798003202');
        $this->addSql('DROP INDEX IDX_540DEA37CCC80CB3');
        $this->addSql('DROP INDEX IDX_540DEA375CC5DB90');
        $this->addSql('DROP INDEX IDX_540DEA3785E0BC94');
        $this->addSql('DROP INDEX IDX_540DEA37B092B37B');
        $this->addSql('ALTER TABLE mia_product DROP model_id');
        $this->addSql('ALTER TABLE mia_product DROP color_id');
        $this->addSql('ALTER TABLE mia_product DROP screen_resolution_id');
        $this->addSql('ALTER TABLE mia_product DROP screen_size_id');
        $this->addSql('ALTER TABLE mia_product DROP cpu_id');
        $this->addSql('ALTER TABLE mia_product DROP gpu_id');
        $this->addSql('ALTER TABLE mia_product DROP memory_id');
        $this->addSql('ALTER TABLE mia_product DROP storage_id');
        $this->addSql('ALTER TABLE mia_product DROP op_sys_id');
        $this->addSql('ALTER TABLE mia_product DROP conditium_id');
        $this->addSql('ALTER TABLE mia_product DROP weight');
    }
}
