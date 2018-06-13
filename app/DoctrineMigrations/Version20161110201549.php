<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161110201549 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_variant (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', article VARCHAR(155) DEFAULT NULL, ean13 VARCHAR(13) DEFAULT NULL, upc VARCHAR(12) DEFAULT NULL, price DOUBLE PRECISION DEFAULT \'0.000000\' NOT NULL, sale_price DOUBLE PRECISION DEFAULT \'0.000000\' NOT NULL, quantity INT DEFAULT 0 NOT NULL, INDEX IDX_209AA41D4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_variant_attribute (product_variant_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', attribute_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_AD0306FBA80EF684 (product_variant_id), INDEX IDX_AD0306FBB6E62EFA (attribute_id), PRIMARY KEY(product_variant_id, attribute_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_variant_attribute ADD CONSTRAINT FK_AD0306FBA80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_attribute ADD CONSTRAINT FK_AD0306FBB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_variant_attribute DROP FOREIGN KEY FK_AD0306FBA80EF684');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('DROP TABLE product_variant_attribute');
    }
}
