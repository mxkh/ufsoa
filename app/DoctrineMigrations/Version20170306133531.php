<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170306133531 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manufacturer CHANGE website website VARCHAR(128) DEFAULT NULL');
        $this->addSql('ALTER TABLE manufacturer_translation ADD slug VARCHAR(155) DEFAULT NULL, ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4BE5154989D9B62 ON manufacturer_translation (slug)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manufacturer CHANGE website website VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('DROP INDEX UNIQ_B4BE5154989D9B62 ON manufacturer_translation');
        $this->addSql('ALTER TABLE manufacturer_translation DROP slug, DROP description');
    }
}
