<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161117123723 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_media_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, alt VARCHAR(155) NOT NULL, INDEX IDX_F78405AF2C2AC5D3 (translatable_id), UNIQUE INDEX product_media_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_media_translation ADD CONSTRAINT FK_F78405AF2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES product_media (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_media DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE media DROP created_at, DROP updated_at');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_media_translation');
        $this->addSql('ALTER TABLE media ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE product_media ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }
}
