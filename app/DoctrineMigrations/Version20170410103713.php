<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170410103713 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shipping_address (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', fullname VARCHAR(155) NOT NULL, address_line VARCHAR(155) NOT NULL, address_line2 VARCHAR(155) DEFAULT NULL, city VARCHAR(155) NOT NULL, region VARCHAR(155) NOT NULL, zip VARCHAR(155) DEFAULT NULL, country VARCHAR(155) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD shipping_address_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES shipping_address (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4D4CFF2B ON orders (shipping_address_id)');
        $this->addSql('ALTER TABLE shopping_cart ADD archived TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4D4CFF2B');
        $this->addSql('DROP TABLE shipping_address');
        $this->addSql('DROP INDEX IDX_E52FFDEE4D4CFF2B ON orders');
        $this->addSql('ALTER TABLE orders DROP shipping_address_id');
        $this->addSql('ALTER TABLE shopping_cart DROP archived');
    }
}
