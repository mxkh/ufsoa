<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161117131436 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE gender (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gender_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_74E3F4222C2AC5D3 (translatable_id), UNIQUE INDEX gender_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', gender_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', firstname VARCHAR(155) DEFAULT NULL, lastname VARCHAR(155) DEFAULT NULL, email VARCHAR(155) NOT NULL, phone VARCHAR(155) NOT NULL, birthday DATE DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_81398E09D2919A68 (customer_group_id), INDEX IDX_81398E09708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_group_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_85C0A7B32C2AC5D3 (translatable_id), UNIQUE INDEX customer_group_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gender_translation ADD CONSTRAINT FK_74E3F4222C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES gender (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09D2919A68 FOREIGN KEY (customer_group_id) REFERENCES customer_group (id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('ALTER TABLE customer_group_translation ADD CONSTRAINT FK_85C0A7B32C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES customer_group (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE gender_translation DROP FOREIGN KEY FK_74E3F4222C2AC5D3');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09708A0E0');
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09D2919A68');
        $this->addSql('ALTER TABLE customer_group_translation DROP FOREIGN KEY FK_85C0A7B32C2AC5D3');
        $this->addSql('DROP TABLE gender');
        $this->addSql('DROP TABLE gender_translation');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE customer_group');
        $this->addSql('DROP TABLE customer_group_translation');
    }
}
