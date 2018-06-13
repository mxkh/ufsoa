<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170310161312 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX product_variant_idx ON product_variant');
        $this->addSql('ALTER TABLE product_variant CHANGE variant_hash variant_hash VARCHAR(32) DEFAULT NULL');
        $this->addSql('ALTER TABLE customer CHANGE phone phone VARCHAR(155) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer CHANGE phone phone VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE product_variant CHANGE variant_hash variant_hash VARCHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX product_variant_idx ON product_variant (supplier_id, variant_hash)');
    }
}
