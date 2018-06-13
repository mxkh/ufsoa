<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161102120836 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE contact_enum DROP value');
        $this->addSql('ALTER TABLE settings_attribute DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE shop_settings DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE social_profile_enum DROP name, DROP alias');
        $this->addSql('ALTER TABLE store DROP address, DROP description, DROP schedule, DROP slug');
        $this->addSql('ALTER TABLE store_enum DROP name');
        $this->addSql('ALTER TABLE manufacturer DROP name, DROP address');
        $this->addSql('ALTER TABLE manufacturer_translation ADD CONSTRAINT FK_B4BE51542C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES manufacturer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supplier DROP name, DROP description');
        $this->addSql('ALTER TABLE supplier_translation ADD CONSTRAINT FK_6DDEEE7E2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES supplier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_enum ADD value VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE manufacturer ADD name VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, ADD address VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE manufacturer_translation DROP FOREIGN KEY FK_B4BE51542C2AC5D3');
        $this->addSql('ALTER TABLE settings_attribute ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE shop_settings ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE social_profile_enum ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD alias VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE store ADD address TINYTEXT NOT NULL COLLATE utf8mb4_unicode_ci, ADD description TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD schedule LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD slug TINYTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE store_enum ADD name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE supplier ADD name VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, ADD description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE supplier_translation DROP FOREIGN KEY FK_6DDEEE7E2C2AC5D3');
    }
}
