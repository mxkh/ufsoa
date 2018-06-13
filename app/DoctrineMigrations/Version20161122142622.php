<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161122142622 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE supplier_attribute_mapping (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', attribute_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_attribute_key VARCHAR(155) NOT NULL, supplier_attribute_value VARCHAR(155) NOT NULL, INDEX IDX_FFA726B2ADD6D8C (supplier_id), INDEX IDX_FFA726BB6E62EFA (attribute_id), UNIQUE INDEX attribute_mapping_idx (supplier_id, supplier_attribute_key, supplier_attribute_value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_feature_mapping (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', feature_value_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_feature_key VARCHAR(155) NOT NULL, supplier_feature_value VARCHAR(155) NOT NULL, INDEX IDX_11F267F72ADD6D8C (supplier_id), INDEX IDX_11F267F780CD149D (feature_value_id), UNIQUE INDEX feature_mapping_idx (supplier_id, supplier_feature_key, supplier_feature_value), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE supplier_attribute_mapping ADD CONSTRAINT FK_FFA726B2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE supplier_attribute_mapping ADD CONSTRAINT FK_FFA726BB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE supplier_feature_mapping ADD CONSTRAINT FK_11F267F72ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE supplier_feature_mapping ADD CONSTRAINT FK_11F267F780CD149D FOREIGN KEY (feature_value_id) REFERENCES feature_value (id)');
        $this->addSql('ALTER TABLE supplier_manufacturer_mapping CHANGE manufacturer_id manufacturer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4584665A');
        $this->addSql('ALTER TABLE product_variant ADD supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD variant_hash VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_209AA41D2ADD6D8C ON product_variant (supplier_id)');
        $this->addSql('CREATE UNIQUE INDEX product_variant_idx ON product_variant (supplier_id, variant_hash)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE supplier_attribute_mapping');
        $this->addSql('DROP TABLE supplier_feature_mapping');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D2ADD6D8C');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4584665A');
        $this->addSql('DROP INDEX IDX_209AA41D2ADD6D8C ON product_variant');
        $this->addSql('DROP INDEX product_variant_idx ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP supplier_id, DROP variant_hash');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE supplier_manufacturer_mapping CHANGE manufacturer_id manufacturer_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\'');
    }
}
