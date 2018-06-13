<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170320124200 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE department (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_variant_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', store_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', article VARCHAR(155) DEFAULT NULL, ean13 VARCHAR(13) DEFAULT NULL, upc VARCHAR(12) DEFAULT NULL, price DOUBLE PRECISION DEFAULT \'0.000000\' NOT NULL, sale_price DOUBLE PRECISION DEFAULT \'0.000000\' NOT NULL, quantity INT DEFAULT 0 NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CD1DE18AA80EF684 (product_variant_id), INDEX IDX_CD1DE18A2ADD6D8C (supplier_id), INDEX IDX_CD1DE18AB092A811 (store_id), INDEX IDX_CD1DE18A4D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variant_import (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', product_variant_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_reference VARCHAR(155) NOT NULL, INDEX IDX_881469CB2ADD6D8C (supplier_id), INDEX IDX_881469CBA80EF684 (product_variant_id), INDEX IDX_881469CB4D16C4DD (shop_id), UNIQUE INDEX variant_import_idx (shop_id, supplier_id, supplier_reference), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AA80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18AB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE department ADD CONSTRAINT FK_CD1DE18A4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE variant_import ADD CONSTRAINT FK_881469CB2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE variant_import ADD CONSTRAINT FK_881469CBA80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
        $this->addSql('ALTER TABLE variant_import ADD CONSTRAINT FK_881469CB4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE supplier ADD position INT NOT NULL');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD802D554');
        $this->addSql('DROP INDEX product_idx ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADD802D554 ON product');
        $this->addSql('ALTER TABLE product ADD manufacturer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD article VARCHAR(155) DEFAULT NULL, ADD out_of_stock TINYINT(1) DEFAULT \'0\' NOT NULL, ADD is_hidden TINYINT(1) DEFAULT \'0\' NOT NULL, DROP product_import_id');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADA23B42D ON product (manufacturer_id)');
        $this->addSql('ALTER TABLE product_import DROP FOREIGN KEY FK_FF328FB9A23B42D');
        $this->addSql('DROP INDEX IDX_FF328FB9A23B42D ON product_import');
        $this->addSql('DROP INDEX product_import_idx ON product_import');
        $this->addSql('ALTER TABLE product_import ADD shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', DROP created_at, DROP updated_at, CHANGE manufacturer_id product_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_import ADD CONSTRAINT FK_FF328FB94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_import ADD CONSTRAINT FK_FF328FB94D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_FF328FB94584665A ON product_import (product_id)');
        $this->addSql('CREATE INDEX IDX_FF328FB94D16C4DD ON product_import (shop_id)');
        $this->addSql('CREATE UNIQUE INDEX product_import_idx ON product_import (shop_id, supplier_id, supplier_reference)');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41D2ADD6D8C');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41DB092A811');
        $this->addSql('DROP INDEX IDX_209AA41D2ADD6D8C ON product_variant');
        $this->addSql('DROP INDEX IDX_209AA41DB092A811 ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP supplier_id, DROP store_id, DROP article, DROP ean13, DROP upc, DROP price, DROP sale_price, DROP quantity, DROP variant_hash');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE variant_import');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA23B42D');
        $this->addSql('DROP INDEX IDX_D34A04ADA23B42D ON product');
        $this->addSql('ALTER TABLE product ADD product_import_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', DROP manufacturer_id, DROP article, DROP out_of_stock, DROP is_hidden');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD802D554 FOREIGN KEY (product_import_id) REFERENCES product_import (id)');
        $this->addSql('CREATE UNIQUE INDEX product_idx ON product (shop_id, product_import_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADD802D554 ON product (product_import_id)');
        $this->addSql('ALTER TABLE product_import DROP FOREIGN KEY FK_FF328FB94584665A');
        $this->addSql('ALTER TABLE product_import DROP FOREIGN KEY FK_FF328FB94D16C4DD');
        $this->addSql('DROP INDEX IDX_FF328FB94584665A ON product_import');
        $this->addSql('DROP INDEX IDX_FF328FB94D16C4DD ON product_import');
        $this->addSql('DROP INDEX product_import_idx ON product_import');
        $this->addSql('ALTER TABLE product_import ADD manufacturer_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL, DROP product_id, DROP shop_id');
        $this->addSql('ALTER TABLE product_import ADD CONSTRAINT FK_FF328FB9A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
        $this->addSql('CREATE INDEX IDX_FF328FB9A23B42D ON product_import (manufacturer_id)');
        $this->addSql('CREATE UNIQUE INDEX product_import_idx ON product_import (supplier_id, supplier_reference)');
        $this->addSql('ALTER TABLE product_variant ADD supplier_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', ADD store_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', ADD article VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD ean13 VARCHAR(13) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD upc VARCHAR(12) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, ADD sale_price DOUBLE PRECISION DEFAULT \'0\' NOT NULL, ADD quantity INT DEFAULT 0 NOT NULL, ADD variant_hash VARCHAR(32) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41DB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE INDEX IDX_209AA41D2ADD6D8C ON product_variant (supplier_id)');
        $this->addSql('CREATE INDEX IDX_209AA41DB092A811 ON product_variant (store_id)');
        $this->addSql('ALTER TABLE supplier DROP position');
    }
}
