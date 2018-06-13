<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170224123327 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE city (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(155) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city_delivery (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', delivery_group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', city_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B25994769F35382B (delivery_group_id), INDEX IDX_B25994768BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(155) NOT NULL, UNIQUE INDEX UNIQ_3781EC1077153098 (code), INDEX IDX_3781EC10FE54D947 (group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(155) NOT NULL, UNIQUE INDEX UNIQ_7DCBA25777153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_group_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(155) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_8A95964F2C2AC5D3 (translatable_id), UNIQUE INDEX delivery_group_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delivery_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_8874E4292C2AC5D3 (translatable_id), UNIQUE INDEX delivery_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_delivery (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', delivery_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_EC3916864D16C4DD (shop_id), INDEX IDX_EC39168612136921 (delivery_id), UNIQUE INDEX shop_delivery_idx (shop_id, delivery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_delivery_city (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', city_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_delivery_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_E2DB5DA14D16C4DD (shop_id), INDEX IDX_E2DB5DA18BAC62AF (city_id), INDEX IDX_E2DB5DA1C0649F31 (shop_delivery_id), UNIQUE INDEX shop_delivery_city_idx (shop_id, city_id, shop_delivery_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_delivery_city_payment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_delivery_city_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', shop_payment_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_176872464D16C4DD (shop_id), INDEX IDX_17687246A0EFDAC0 (shop_delivery_city_id), INDEX IDX_176872467EB04E9D (shop_payment_id), UNIQUE INDEX shop_delivery_city_payment_idx (shop_id, shop_delivery_city_id, shop_payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city_delivery ADD CONSTRAINT FK_B25994769F35382B FOREIGN KEY (delivery_group_id) REFERENCES delivery_group (id)');
        $this->addSql('ALTER TABLE city_delivery ADD CONSTRAINT FK_B25994768BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE delivery ADD CONSTRAINT FK_3781EC10FE54D947 FOREIGN KEY (group_id) REFERENCES delivery_group (id)');
        $this->addSql('ALTER TABLE delivery_group_translation ADD CONSTRAINT FK_8A95964F2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES delivery_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delivery_translation ADD CONSTRAINT FK_8874E4292C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES delivery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_delivery ADD CONSTRAINT FK_EC3916864D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_delivery ADD CONSTRAINT FK_EC39168612136921 FOREIGN KEY (delivery_id) REFERENCES delivery (id)');
        $this->addSql('ALTER TABLE shop_delivery_city ADD CONSTRAINT FK_E2DB5DA14D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_delivery_city ADD CONSTRAINT FK_E2DB5DA18BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE shop_delivery_city ADD CONSTRAINT FK_E2DB5DA1C0649F31 FOREIGN KEY (shop_delivery_id) REFERENCES shop_delivery (id)');
        $this->addSql('ALTER TABLE shop_delivery_city_payment ADD CONSTRAINT FK_176872464D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_delivery_city_payment ADD CONSTRAINT FK_17687246A0EFDAC0 FOREIGN KEY (shop_delivery_city_id) REFERENCES shop_delivery_city (id)');
        $this->addSql('ALTER TABLE shop_delivery_city_payment ADD CONSTRAINT FK_176872467EB04E9D FOREIGN KEY (shop_payment_id) REFERENCES shop_payment (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE city_delivery DROP FOREIGN KEY FK_B25994768BAC62AF');
        $this->addSql('ALTER TABLE shop_delivery_city DROP FOREIGN KEY FK_E2DB5DA18BAC62AF');
        $this->addSql('ALTER TABLE delivery_translation DROP FOREIGN KEY FK_8874E4292C2AC5D3');
        $this->addSql('ALTER TABLE shop_delivery DROP FOREIGN KEY FK_EC39168612136921');
        $this->addSql('ALTER TABLE city_delivery DROP FOREIGN KEY FK_B25994769F35382B');
        $this->addSql('ALTER TABLE delivery DROP FOREIGN KEY FK_3781EC10FE54D947');
        $this->addSql('ALTER TABLE delivery_group_translation DROP FOREIGN KEY FK_8A95964F2C2AC5D3');
        $this->addSql('ALTER TABLE shop_delivery_city DROP FOREIGN KEY FK_E2DB5DA1C0649F31');
        $this->addSql('ALTER TABLE shop_delivery_city_payment DROP FOREIGN KEY FK_17687246A0EFDAC0');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE city_delivery');
        $this->addSql('DROP TABLE delivery');
        $this->addSql('DROP TABLE delivery_group');
        $this->addSql('DROP TABLE delivery_group_translation');
        $this->addSql('DROP TABLE delivery_translation');
        $this->addSql('DROP TABLE shop_delivery');
        $this->addSql('DROP TABLE shop_delivery_city');
        $this->addSql('DROP TABLE shop_delivery_city_payment');
    }
}
