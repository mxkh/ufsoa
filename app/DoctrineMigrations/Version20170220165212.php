<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170220165212 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders ADD shop_payment_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD shop_currency_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD payment_url LONGTEXT DEFAULT NULL, ADD number VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE7EB04E9D FOREIGN KEY (shop_payment_id) REFERENCES shop_payment (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEEEA537766 FOREIGN KEY (shop_currency_id) REFERENCES shop_currency (id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEE7EB04E9D ON orders (shop_payment_id)');
        $this->addSql('CREATE INDEX IDX_E52FFDEEEA537766 ON orders (shop_currency_id)');
        $this->addSql('CREATE INDEX number_idx ON orders (number)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEE7EB04E9D');
        $this->addSql('ALTER TABLE orders DROP FOREIGN KEY FK_E52FFDEEEA537766');
        $this->addSql('DROP INDEX IDX_E52FFDEE7EB04E9D ON orders');
        $this->addSql('DROP INDEX IDX_E52FFDEEEA537766 ON orders');
        $this->addSql('DROP INDEX number_idx ON orders');
        $this->addSql('ALTER TABLE orders DROP shop_payment_id, DROP shop_currency_id, DROP payment_url, DROP number');
    }
}
