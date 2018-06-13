<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161128175338 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shopping_cart_item (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shopping_cart_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', product_variant_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_E59A1DF445F80CD (shopping_cart_id), INDEX IDX_E59A1DF4A80EF684 (product_variant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF445F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id)');
        $this->addSql('ALTER TABLE shopping_cart_item ADD CONSTRAINT FK_E59A1DF4A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shopping_cart_item');
    }
}
