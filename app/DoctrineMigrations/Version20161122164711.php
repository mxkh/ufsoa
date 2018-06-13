<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161122164711 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE order_item (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', order_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', quantity INT NOT NULL, price DOUBLE PRECISION NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_52EA1F098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE orders (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', quantity INT NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_E52FFDEE9395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE orders');
    }
}
