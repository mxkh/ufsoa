<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161205143637 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE import (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', media_enum_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', version VARCHAR(155) NOT NULL, filename VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, status SMALLINT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9D4ECE1D76B5081C (media_enum_id), INDEX IDX_9D4ECE1D2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE import ADD CONSTRAINT FK_9D4ECE1D76B5081C FOREIGN KEY (media_enum_id) REFERENCES media_enum (id)');
        $this->addSql('ALTER TABLE import ADD CONSTRAINT FK_9D4ECE1D2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE import');
    }
}
