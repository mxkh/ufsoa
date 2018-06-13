<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161007091804 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE store_shop (store_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_4ED3E26CB092A811 (store_id), INDEX IDX_4ED3E26C4D16C4DD (shop_id), PRIMARY KEY(store_id, shop_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE store_shop ADD CONSTRAINT FK_4ED3E26CB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_shop ADD CONSTRAINT FK_4ED3E26C4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE store_shop');
    }
}
