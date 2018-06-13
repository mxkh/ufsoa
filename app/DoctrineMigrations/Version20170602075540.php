<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170602075540 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE4D4CFF2B');
        $this->addSql('DROP TABLE shipping_address');
        $this->addSql('ALTER TABLE customer_address ADD shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD city_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD delivery_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD street_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD branch_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD firstname VARCHAR(155) DEFAULT NULL, ADD lastname VARCHAR(155) DEFAULT NULL, ADD phone VARCHAR(155) DEFAULT NULL, ADD apartment VARCHAR(155) DEFAULT NULL, DROP fullname, DROP address_line, DROP address_line2, DROP city, DROP region, CHANGE customer_id customer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', CHANGE zip zip VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F12136921 FOREIGN KEY (delivery_id) REFERENCES shop_delivery (id)');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3F87CF8EB FOREIGN KEY (street_id) REFERENCES street (id)');
        $this->addSql('ALTER TABLE customer_address ADD CONSTRAINT FK_1193CB3FDCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id)');
        $this->addSql('CREATE INDEX IDX_1193CB3F4D16C4DD ON customer_address (shop_id)');
        $this->addSql('CREATE INDEX IDX_1193CB3F8BAC62AF ON customer_address (city_id)');
        $this->addSql('CREATE INDEX IDX_1193CB3F12136921 ON customer_address (delivery_id)');
        $this->addSql('CREATE INDEX IDX_1193CB3F87CF8EB ON customer_address (street_id)');
        $this->addSql('CREATE INDEX IDX_1193CB3FDCD6CC49 ON customer_address (branch_id)');
        $this->addSql('DROP INDEX IDX_E52FFDEE4D4CFF2B ON orders');
        $this->addSql('ALTER TABLE orders DROP shipping_address_id');
        $this->addSql('ALTER TABLE orders ADD customer_address_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE87EABF7 FOREIGN KEY (customer_address_id) REFERENCES customer_address (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE87EABF7 ON orders (customer_address_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shipping_address (id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', fullname VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, address_line VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, address_line2 VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, city VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, region VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, zip VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, country VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, phone VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, email VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F4D16C4DD');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F8BAC62AF');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F12136921');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3F87CF8EB');
        $this->addSql('ALTER TABLE customer_address DROP FOREIGN KEY FK_1193CB3FDCD6CC49');
        $this->addSql('DROP INDEX IDX_1193CB3F4D16C4DD ON customer_address');
        $this->addSql('DROP INDEX IDX_1193CB3F8BAC62AF ON customer_address');
        $this->addSql('DROP INDEX IDX_1193CB3F12136921 ON customer_address');
        $this->addSql('DROP INDEX IDX_1193CB3F87CF8EB ON customer_address');
        $this->addSql('DROP INDEX IDX_1193CB3FDCD6CC49 ON customer_address');
        $this->addSql('ALTER TABLE customer_address ADD fullname VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD address_line VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD address_line2 VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD city VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD region VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP shop_id, DROP city_id, DROP delivery_id, DROP street_id, DROP branch_id, DROP firstname, DROP lastname, DROP phone, DROP apartment, CHANGE customer_id customer_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', CHANGE zip zip VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE87EABF7');
        $this->addSql('DROP INDEX IDX_E52FFDEE87EABF7 ON orders');
        $this->addSql('ALTER TABLE orders DROP customer_address_id');
        $this->addSql('ALTER TABLE orders ADD shipping_address_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE4D4CFF2B FOREIGN KEY (shipping_address_id) REFERENCES shipping_address (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE4D4CFF2B ON orders (shipping_address_id)');
    }
}
