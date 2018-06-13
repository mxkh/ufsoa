<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170203113139 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE promocode (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', promocode_enum_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(64) NOT NULL, value INT NOT NULL, start DATETIME DEFAULT NULL, finish DATETIME DEFAULT NULL, is_reusable TINYINT(1) DEFAULT \'1\' NOT NULL, limiting INT DEFAULT 0, used INT DEFAULT 0 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_7C786E0677153098 (code), INDEX IDX_7C786E069395C3F3 (customer_id), INDEX IDX_7C786E06A59FD375 (promocode_enum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promocode_enum (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(155) NOT NULL, calculate VARCHAR(155) NOT NULL, UNIQUE INDEX UNIQ_DE4AA4C877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promocode_enum_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) DEFAULT NULL, INDEX IDX_A8859D772C2AC5D3 (translatable_id), UNIQUE INDEX promocode_enum_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE promocode ADD CONSTRAINT FK_7C786E069395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE promocode ADD CONSTRAINT FK_7C786E06A59FD375 FOREIGN KEY (promocode_enum_id) REFERENCES promocode_enum (id)');
        $this->addSql('ALTER TABLE promocode_enum_translation ADD CONSTRAINT FK_A8859D772C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES promocode_enum (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE promocode DROP FOREIGN KEY FK_7C786E06A59FD375');
        $this->addSql('ALTER TABLE promocode_enum_translation DROP FOREIGN KEY FK_A8859D772C2AC5D3');
        $this->addSql('DROP TABLE promocode');
        $this->addSql('DROP TABLE promocode_enum');
        $this->addSql('DROP TABLE promocode_enum_translation');
    }
}
