<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161007141328 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shop_product (shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_D07944874D16C4DD (shop_id), INDEX IDX_D07944874584665A (product_id), PRIMARY KEY(shop_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_product (supplier_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_522F70B22ADD6D8C (supplier_id), INDEX IDX_522F70B24584665A (product_id), PRIMARY KEY(supplier_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', manufacturer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', article VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, short_description VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, min_price INT DEFAULT NULL, max_price INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D34A04ADA23B42D (manufacturer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D07944874D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_product ADD CONSTRAINT FK_D07944874584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_product ADD CONSTRAINT FK_522F70B22ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_product ADD CONSTRAINT FK_522F70B24584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_product DROP FOREIGN KEY FK_D07944874584665A');
        $this->addSql('ALTER TABLE supplier_product DROP FOREIGN KEY FK_522F70B24584665A');
        $this->addSql('DROP TABLE shop_product');
        $this->addSql('DROP TABLE supplier_product');
        $this->addSql('DROP TABLE product');
    }
}
