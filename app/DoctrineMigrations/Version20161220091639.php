<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161220091639 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_seo (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_8C5EB82F4584665A (product_id), INDEX IDX_8C5EB82F4D16C4DD (shop_id), UNIQUE INDEX product_seo_idx (product_id, shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_seo_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, title VARCHAR(155) DEFAULT NULL, keywords VARCHAR(155) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_4285697E2C2AC5D3 (translatable_id), UNIQUE INDEX product_seo_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_seo ADD CONSTRAINT FK_8C5EB82F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_seo ADD CONSTRAINT FK_8C5EB82F4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE product_seo_translation ADD CONSTRAINT FK_4285697E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES product_seo (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_seo_translation DROP FOREIGN KEY FK_4285697E2C2AC5D3');
        $this->addSql('DROP TABLE product_seo');
        $this->addSql('DROP TABLE product_seo_translation');
    }
}
