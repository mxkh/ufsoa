<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161021040115 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact_enum_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, value VARCHAR(128) NOT NULL, INDEX IDX_77061A302C2AC5D3 (translatable_id), UNIQUE INDEX contact_enum_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE social_profile_enum_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, alias VARCHAR(128) NOT NULL, INDEX IDX_31FBB19B2C2AC5D3 (translatable_id), UNIQUE INDEX social_profile_enum_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_enum_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, value VARCHAR(128) NOT NULL, INDEX IDX_CA41837A2C2AC5D3 (translatable_id), UNIQUE INDEX store_enum_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, address VARCHAR(155) NOT NULL, description VARCHAR(155) NOT NULL, slug VARCHAR(155) NOT NULL, INDEX IDX_6AFC32F12C2AC5D3 (translatable_id), UNIQUE INDEX store_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_enum_translation ADD CONSTRAINT FK_77061A302C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES contact_enum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE social_profile_enum_translation ADD CONSTRAINT FK_31FBB19B2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES social_profile_enum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_enum_translation ADD CONSTRAINT FK_CA41837A2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES store_enum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_translation ADD CONSTRAINT FK_6AFC32F12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE settings_attribute ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE shop_settings ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contact_enum_translation');
        $this->addSql('DROP TABLE social_profile_enum_translation');
        $this->addSql('DROP TABLE store_enum_translation');
        $this->addSql('DROP TABLE store_translation');
        $this->addSql('ALTER TABLE settings_attribute DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE shop_settings DROP created_at, DROP updated_at');
    }
}
