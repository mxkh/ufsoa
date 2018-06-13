<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170301122114 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_import (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', manufacturer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_reference VARCHAR(155) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FF328FB9A23B42D (manufacturer_id), INDEX IDX_FF328FB92ADD6D8C (supplier_id), UNIQUE INDEX product_import_idx (supplier_id, supplier_reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_import ADD CONSTRAINT FK_FF328FB9A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('ALTER TABLE product_import ADD CONSTRAINT FK_FF328FB92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('DROP TABLE shop_product');
        $this->addSql('DROP TABLE supplier_product');
        $this->addSql('ALTER TABLE import ADD shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE import ADD CONSTRAINT FK_9D4ECE1D4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_9D4ECE1D4D16C4DD ON import (shop_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA23B42D');
        $this->addSql('DROP INDEX IDX_D34A04ADA23B42D ON product');
        $this->addSql('ALTER TABLE product ADD product_import_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP article, CHANGE manufacturer_id shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD802D554 FOREIGN KEY (product_import_id) REFERENCES product_import (id)');
        $this->addSql('CREATE INDEX IDX_D34A04AD4D16C4DD ON product (shop_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADD802D554 ON product (product_import_id)');
        $this->addSql('CREATE UNIQUE INDEX product_idx ON product (shop_id, product_import_id)');
        $this->addSql('ALTER TABLE product_variant ADD shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_209AA41D4D16C4DD ON product_variant (shop_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD802D554');
        $this->addSql('CREATE TABLE shop_product (shop_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_D07944874D16C4DD (shop_id), INDEX IDX_D07944874584665A (product_id), PRIMARY KEY(shop_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_product (supplier_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_522F70B22ADD6D8C (supplier_id), INDEX IDX_522F70B24584665A (product_id), PRIMARY KEY(supplier_id, product_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D07944874584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D07944874D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_product ADD CONSTRAINT FK_522F70B22ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_product ADD CONSTRAINT FK_522F70B24584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE product_import');
        $this->addSql('ALTER TABLE import DROP FOREIGN KEY FK_9D4ECE1D4D16C4DD');
        $this->addSql('DROP INDEX IDX_9D4ECE1D4D16C4DD ON import');
        $this->addSql('ALTER TABLE import DROP shop_id');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4D16C4DD');
        $this->addSql('DROP INDEX IDX_D34A04AD4D16C4DD ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADD802D554 ON product');
        $this->addSql('DROP INDEX product_idx ON product');
        $this->addSql('ALTER TABLE product ADD article VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, DROP product_import_id, CHANGE shop_id manufacturer_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA23B42D ON product (manufacturer_id)');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D4D16C4DD');
        $this->addSql('DROP INDEX IDX_209AA41D4D16C4DD ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP shop_id');
    }
}
