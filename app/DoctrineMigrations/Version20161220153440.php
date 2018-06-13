<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161220153440 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_seo (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_49B3416A12469DE2 (category_id), INDEX IDX_49B3416A4D16C4DD (shop_id), UNIQUE INDEX category_seo_idx (category_id, shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_seo_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, title VARCHAR(155) DEFAULT NULL, keywords VARCHAR(155) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_288730B12C2AC5D3 (translatable_id), UNIQUE INDEX category_seo_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_seo ADD CONSTRAINT FK_49B3416A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE category_seo ADD CONSTRAINT FK_49B3416A4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE category_seo_translation ADD CONSTRAINT FK_288730B12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES category_seo (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_seo_translation DROP FOREIGN KEY FK_288730B12C2AC5D3');
        $this->addSql('DROP TABLE category_seo');
        $this->addSql('DROP TABLE category_seo_translation');
    }
}
