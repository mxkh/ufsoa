<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170321131405 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE manufacturer ADD name VARCHAR(128) NOT NULL, ADD slug VARCHAR(155) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3D0AE6DC989D9B62 ON manufacturer (slug)');
        $this->addSql('DROP INDEX UNIQ_B4BE5154989D9B62 ON manufacturer_translation');
        $this->addSql('ALTER TABLE manufacturer_translation DROP name, DROP slug');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_3D0AE6DC989D9B62 ON manufacturer');
        $this->addSql('ALTER TABLE manufacturer DROP name, DROP slug');
        $this->addSql('ALTER TABLE manufacturer_translation ADD name VARCHAR(128) NOT NULL COLLATE utf8mb4_unicode_ci, ADD slug VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B4BE5154989D9B62 ON manufacturer_translation (slug)');
    }
}
