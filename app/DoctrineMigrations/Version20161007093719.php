<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161007093719 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE manufacturer_shop (manufacturer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_29217D6DA23B42D (manufacturer_id), INDEX IDX_29217D6D4D16C4DD (shop_id), PRIMARY KEY(manufacturer_id, shop_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE manufacturer_shop ADD CONSTRAINT FK_29217D6DA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE manufacturer_shop ADD CONSTRAINT FK_29217D6D4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shop_manufacturer');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE shop_manufacturer (shop_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', manufacturer_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_206F814E4D16C4DD (shop_id), INDEX IDX_206F814EA23B42D (manufacturer_id), PRIMARY KEY(shop_id, manufacturer_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_manufacturer ADD CONSTRAINT FK_206F814E4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_manufacturer ADD CONSTRAINT FK_206F814EA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE manufacturer_shop');
    }
}
