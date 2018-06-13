<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170207134245 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_type_translation DROP FOREIGN KEY FK_B0A8E8402C2AC5D3');
        $this->addSql('CREATE TABLE shop_payment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', payment_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_6E1BC4274D16C4DD (shop_id), INDEX IDX_6E1BC4274C3A3BB (payment_id), UNIQUE INDEX shop_payment_idx (shop_id, payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shop_payment_settings (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_payment_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', public_key VARCHAR(255) DEFAULT NULL, private_key VARCHAR(255) DEFAULT NULL, return_url VARCHAR(255) DEFAULT NULL, merchant_auth_type VARCHAR(255) DEFAULT NULL, merchant_transaction_type VARCHAR(255) DEFAULT NULL, is_sandbox TINYINT(1) DEFAULT \'0\' NOT NULL, UNIQUE INDEX UNIQ_84DEE51C7EB04E9D (shop_payment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(155) NOT NULL, type SMALLINT DEFAULT 0 NOT NULL, UNIQUE INDEX UNIQ_6D28840D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_42E595B52C2AC5D3 (translatable_id), UNIQUE INDEX payment_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC4274D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE shop_payment ADD CONSTRAINT FK_6E1BC4274C3A3BB FOREIGN KEY (payment_id) REFERENCES payment (id)');
        $this->addSql('ALTER TABLE shop_payment_settings ADD CONSTRAINT FK_84DEE51C7EB04E9D FOREIGN KEY (shop_payment_id) REFERENCES shop_payment (id)');
        $this->addSql('ALTER TABLE payment_translation ADD CONSTRAINT FK_42E595B52C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES payment (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE payment_type');
        $this->addSql('DROP TABLE payment_type_translation');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE shop_payment_settings DROP FOREIGN KEY FK_84DEE51C7EB04E9D');
        $this->addSql('ALTER TABLE shop_payment DROP FOREIGN KEY FK_6E1BC4274C3A3BB');
        $this->addSql('ALTER TABLE payment_translation DROP FOREIGN KEY FK_42E595B52C2AC5D3');
        $this->addSql('CREATE TABLE payment_type (id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', code VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX UNIQ_AD5DC05D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_type_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, name VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, description LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, UNIQUE INDEX payment_type_translation_unique_translation (translatable_id, locale), INDEX IDX_B0A8E8402C2AC5D3 (translatable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_type_translation ADD CONSTRAINT FK_B0A8E8402C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES payment_type (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE shop_payment');
        $this->addSql('DROP TABLE shop_payment_settings');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE payment_translation');
    }
}
