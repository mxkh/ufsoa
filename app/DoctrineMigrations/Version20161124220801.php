<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161124220801 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders ADD shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4D16C4DD ON orders (shop_id)');
        $this->addSql('ALTER TABLE order_item ADD product_variant_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
        $this->addSql('CREATE INDEX IDX_52EA1F09A80EF684 ON order_item (product_variant_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09A80EF684');
        $this->addSql('DROP INDEX IDX_52EA1F09A80EF684 ON order_item');
        $this->addSql('ALTER TABLE order_item DROP product_variant_id');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4D16C4DD');
        $this->addSql('DROP INDEX IDX_E52FFDEE4D16C4DD ON orders');
        $this->addSql('ALTER TABLE orders DROP shop_id');
    }
}
