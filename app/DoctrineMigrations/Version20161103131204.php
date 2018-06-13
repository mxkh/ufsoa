<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161103131204 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE supplier_manufacturer_mapping (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', supplier_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', manufacturer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', supplier_manufacturer VARCHAR(155) NOT NULL, INDEX IDX_6839F3F92ADD6D8C (supplier_id), INDEX IDX_6839F3F9A23B42D (manufacturer_id), UNIQUE INDEX manufacturer_mapping_idx (supplier_id, supplier_manufacturer), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE supplier_manufacturer_mapping ADD CONSTRAINT FK_6839F3F92ADD6D8C FOREIGN KEY (supplier_id) REFERENCES supplier (id)');
        $this->addSql('ALTER TABLE supplier_manufacturer_mapping ADD CONSTRAINT FK_6839F3F9A23B42D FOREIGN KEY (manufacturer_id) REFERENCES manufacturer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE supplier_manufacturer_mapping');
    }
}
