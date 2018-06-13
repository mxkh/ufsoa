<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161116210008 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_media (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', media_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CB70DA504584665A (product_id), INDEX IDX_CB70DA504D16C4DD (shop_id), INDEX IDX_CB70DA50EA9FDD75 (media_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_media ADD CONSTRAINT FK_CB70DA504584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_media ADD CONSTRAINT FK_CB70DA504D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE product_media ADD CONSTRAINT FK_CB70DA50EA9FDD75 FOREIGN KEY (media_id) REFERENCES media (id)');
        $this->addSql('ALTER TABLE media ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_media');
        $this->addSql('ALTER TABLE media DROP created_at, DROP updated_at');
    }
}
