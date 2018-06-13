<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161129182443 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', fullname VARCHAR(155) DEFAULT NULL, address_line VARCHAR(155) DEFAULT NULL, address_line2 VARCHAR(155) DEFAULT NULL, city VARCHAR(155) DEFAULT NULL, region VARCHAR(155) DEFAULT NULL, zip VARCHAR(155) DEFAULT NULL, country VARCHAR(155) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_D4E6F819395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F819395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE address');
    }
}
