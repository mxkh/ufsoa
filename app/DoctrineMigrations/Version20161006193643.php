<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161006193643 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', Value VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_contact_enum (contact_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', contact_enum_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_AF61E9AE7A1254A (contact_id), INDEX IDX_AF61E9A8D4DAFD4 (contact_enum_id), PRIMARY KEY(contact_id, contact_enum_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contact_enum (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', value VARCHAR(32) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE geolocation (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings_attribute (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', attribute VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_AC6A4CA225EC23D1 (shop_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_currency (shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', currency_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B2EE72A94D16C4DD (shop_id), INDEX IDX_B2EE72A938248176 (currency_id), PRIMARY KEY(shop_id, currency_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_language (shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', language_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_F638B234D16C4DD (shop_id), INDEX IDX_F638B2382F1BAF4 (language_id), PRIMARY KEY(shop_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_manufacturer (shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', manufacturer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_206F814E4D16C4DD (shop_id), INDEX IDX_206F814EA23B42D (manufacturer_id), PRIMARY KEY(shop_id, manufacturer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(128) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_settings (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', attribute_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', value VARCHAR(255) NOT NULL, INDEX IDX_3EFD5A534D16C4DD (shop_id), INDEX IDX_3EFD5A53B6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_profile_enum (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, alias VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', store_enum_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', geolocation_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, address TINYTEXT NOT NULL, description TINYTEXT DEFAULT NULL, schedule LONGTEXT DEFAULT NULL, slug TINYTEXT DEFAULT NULL, supplier_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', is_active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_FF575877E6AAFB2 (store_enum_id), INDEX IDX_FF5758771C7B5678 (geolocation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_store_social_profile (store_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', store_social_profile_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_28BC47ADB092A811 (store_id), INDEX IDX_28BC47AD63BEE23A (store_social_profile_id), PRIMARY KEY(store_id, store_social_profile_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_contact (store_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', contact_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_556AC7DFB092A811 (store_id), INDEX IDX_556AC7DFE7A1254A (contact_id), PRIMARY KEY(store_id, contact_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_enum (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_social_profile (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', social_profile_enum_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', value VARCHAR(255) NOT NULL, INDEX IDX_19BA624CDBF1074A (social_profile_enum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currency (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(3) NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(2) NOT NULL, name VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacturer (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(128) NOT NULL, address VARCHAR(128) NOT NULL, website VARCHAR(128) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(128) NOT NULL, description VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) DEFAULT \'1\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supplier_shop (supplier_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_6132BA4B2ADD6D8C (supplier_id), INDEX IDX_6132BA4B4D16C4DD (shop_id), PRIMARY KEY(supplier_id, shop_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_contact_enum ADD CONSTRAINT FK_AF61E9AE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_contact_enum ADD CONSTRAINT FK_AF61E9A8D4DAFD4 FOREIGN KEY (contact_enum_id) REFERENCES contact_enum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop ADD CONSTRAINT FK_AC6A4CA225EC23D1 FOREIGN KEY (shop_group_id) REFERENCES shop_group (id)');
        $this->addSql('ALTER TABLE shop_currency ADD CONSTRAINT FK_B2EE72A94D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_currency ADD CONSTRAINT FK_B2EE72A938248176 FOREIGN KEY (currency_id) REFERENCES currency (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_language ADD CONSTRAINT FK_F638B234D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_language ADD CONSTRAINT FK_F638B2382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_manufacturer ADD CONSTRAINT FK_206F814E4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_manufacturer ADD CONSTRAINT FK_206F814EA23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shop_settings ADD CONSTRAINT FK_3EFD5A534D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_settings ADD CONSTRAINT FK_3EFD5A53B6E62EFA FOREIGN KEY (attribute_id) REFERENCES settings_attribute (id)');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF575877E6AAFB2 FOREIGN KEY (store_enum_id) REFERENCES store_enum (id)');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758771C7B5678 FOREIGN KEY (geolocation_id) REFERENCES geolocation (id)');
        $this->addSql('ALTER TABLE store_store_social_profile ADD CONSTRAINT FK_28BC47ADB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_store_social_profile ADD CONSTRAINT FK_28BC47AD63BEE23A FOREIGN KEY (store_social_profile_id) REFERENCES store_social_profile (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_contact ADD CONSTRAINT FK_556AC7DFB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_contact ADD CONSTRAINT FK_556AC7DFE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_social_profile ADD CONSTRAINT FK_19BA624CDBF1074A FOREIGN KEY (social_profile_enum_id) REFERENCES social_profile_enum (id)');
        $this->addSql('ALTER TABLE supplier_shop ADD CONSTRAINT FK_6132BA4B2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier_shop ADD CONSTRAINT FK_6132BA4B4D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact_contact_enum DROP FOREIGN KEY FK_AF61E9AE7A1254A');
        $this->addSql('ALTER TABLE store_contact DROP FOREIGN KEY FK_556AC7DFE7A1254A');
        $this->addSql('ALTER TABLE contact_contact_enum DROP FOREIGN KEY FK_AF61E9A8D4DAFD4');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758771C7B5678');
        $this->addSql('ALTER TABLE shop_settings DROP FOREIGN KEY FK_3EFD5A53B6E62EFA');
        $this->addSql('ALTER TABLE shop_currency DROP FOREIGN KEY FK_B2EE72A94D16C4DD');
        $this->addSql('ALTER TABLE shop_language DROP FOREIGN KEY FK_F638B234D16C4DD');
        $this->addSql('ALTER TABLE shop_manufacturer DROP FOREIGN KEY FK_206F814E4D16C4DD');
        $this->addSql('ALTER TABLE shop_settings DROP FOREIGN KEY FK_3EFD5A534D16C4DD');
        $this->addSql('ALTER TABLE supplier_shop DROP FOREIGN KEY FK_6132BA4B4D16C4DD');
        $this->addSql('ALTER TABLE shop DROP FOREIGN KEY FK_AC6A4CA225EC23D1');
        $this->addSql('ALTER TABLE store_social_profile DROP FOREIGN KEY FK_19BA624CDBF1074A');
        $this->addSql('ALTER TABLE store_store_social_profile DROP FOREIGN KEY FK_28BC47ADB092A811');
        $this->addSql('ALTER TABLE store_contact DROP FOREIGN KEY FK_556AC7DFB092A811');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF575877E6AAFB2');
        $this->addSql('ALTER TABLE store_store_social_profile DROP FOREIGN KEY FK_28BC47AD63BEE23A');
        $this->addSql('ALTER TABLE shop_currency DROP FOREIGN KEY FK_B2EE72A938248176');
        $this->addSql('ALTER TABLE shop_language DROP FOREIGN KEY FK_F638B2382F1BAF4');
        $this->addSql('ALTER TABLE shop_manufacturer DROP FOREIGN KEY FK_206F814EA23B42D');
        $this->addSql('ALTER TABLE supplier_shop DROP FOREIGN KEY FK_6132BA4B2ADD6D8C');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE contact_contact_enum');
        $this->addSql('DROP TABLE contact_enum');
        $this->addSql('DROP TABLE geolocation');
        $this->addSql('DROP TABLE settings_attribute');
        $this->addSql('DROP TABLE shop');
        $this->addSql('DROP TABLE shop_currency');
        $this->addSql('DROP TABLE shop_language');
        $this->addSql('DROP TABLE shop_manufacturer');
        $this->addSql('DROP TABLE shop_group');
        $this->addSql('DROP TABLE shop_settings');
        $this->addSql('DROP TABLE social_profile_enum');
        $this->addSql('DROP TABLE store');
        $this->addSql('DROP TABLE store_store_social_profile');
        $this->addSql('DROP TABLE store_contact');
        $this->addSql('DROP TABLE store_enum');
        $this->addSql('DROP TABLE store_social_profile');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE manufacturer');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('DROP TABLE supplier_shop');
    }
}
