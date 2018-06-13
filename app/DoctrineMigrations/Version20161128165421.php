<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161128165421 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shopping_cart (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', quantity INT NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_72AAD4F69395C3F3 (customer_id), INDEX IDX_72AAD4F64D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F69395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F64D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE shopping_cart');
    }
}
