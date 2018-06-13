<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161102152626 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE store ADD slug VARCHAR(155) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FF575877989D9B62 ON store (slug)');
        $this->addSql('ALTER TABLE store_translation DROP slug');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_FF575877989D9B62 ON store');
        $this->addSql('ALTER TABLE store DROP slug');
        $this->addSql('ALTER TABLE store_translation ADD slug VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
