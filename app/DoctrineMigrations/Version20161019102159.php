<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161019102159 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attribute (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', attribute_group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', color VARCHAR(32) DEFAULT NULL, position INT DEFAULT 0 NOT NULL, INDEX IDX_FA7AEFFB62D643B7 (attribute_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', attribute_group_enum_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', is_color_group TINYINT(1) DEFAULT \'0\' NOT NULL, position INT DEFAULT 0 NOT NULL, INDEX IDX_8EF8A77334EE813C (attribute_group_enum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_group_enum (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_group_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, public_name VARCHAR(128) NOT NULL, INDEX IDX_CC4A1CEE2C2AC5D3 (translatable_id), UNIQUE INDEX attribute_group_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_89B5B6BF2C2AC5D3 (translatable_id), UNIQUE INDEX attribute_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribute ADD CONSTRAINT FK_FA7AEFFB62D643B7 FOREIGN KEY (attribute_group_id) REFERENCES attribute_group (id)');
        $this->addSql('ALTER TABLE attribute_group ADD CONSTRAINT FK_8EF8A77334EE813C FOREIGN KEY (attribute_group_enum_id) REFERENCES attribute_group_enum (id)');
        $this->addSql('ALTER TABLE attribute_group_translation ADD CONSTRAINT FK_CC4A1CEE2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES attribute_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE attribute_translation ADD CONSTRAINT FK_89B5B6BF2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES attribute (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE attribute_translation DROP FOREIGN KEY FK_89B5B6BF2C2AC5D3');
        $this->addSql('ALTER TABLE attribute DROP FOREIGN KEY FK_FA7AEFFB62D643B7');
        $this->addSql('ALTER TABLE attribute_group_translation DROP FOREIGN KEY FK_CC4A1CEE2C2AC5D3');
        $this->addSql('ALTER TABLE attribute_group DROP FOREIGN KEY FK_8EF8A77334EE813C');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE attribute_group');
        $this->addSql('DROP TABLE attribute_group_enum');
        $this->addSql('DROP TABLE attribute_group_translation');
        $this->addSql('DROP TABLE attribute_translation');
    }
}
