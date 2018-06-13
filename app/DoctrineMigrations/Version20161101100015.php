<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161101100015 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SET foreign_key_checks = 0;');
        $this->addSql('ALTER TABLE product CHANGE manufacturer_id manufacturer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', CHANGE article article VARCHAR(155) NOT NULL, CHANGE slug slug VARCHAR(155) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE short_description short_description LONGTEXT DEFAULT NULL');
        $this->addSql('SET foreign_key_checks = 1;');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('SET foreign_key_checks = 0;');
        $this->addSql('ALTER TABLE product CHANGE manufacturer_id manufacturer_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', CHANGE article article VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE slug slug VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, CHANGE short_description short_description VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('SET foreign_key_checks = 1;');
    }
}
