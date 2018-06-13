<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161117175315 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store CHANGE supplier_id supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758772ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('CREATE INDEX IDX_FF5758772ADD6D8C ON store (supplier_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758772ADD6D8C');
        $this->addSql('DROP INDEX IDX_FF5758772ADD6D8C ON store');
        $this->addSql('ALTER TABLE store CHANGE supplier_id supplier_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\'');
    }
}
