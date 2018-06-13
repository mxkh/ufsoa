<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161206162225 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09708A0E0');
        $this->addSql('DROP INDEX IDX_81398E09708A0E0 ON customer');
        $this->addSql('ALTER TABLE customer ADD password VARCHAR(155) DEFAULT NULL, ADD confirmation_code VARCHAR(32) DEFAULT NULL, ADD is_confirmed TINYINT(1) DEFAULT \'0\' NOT NULL, DROP gender_id, DROP firstname, DROP lastname, DROP birthday, CHANGE email email VARCHAR(155) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer ADD gender_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', ADD lastname VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD birthday DATE DEFAULT NULL, DROP confirmation_code, DROP is_confirmed, CHANGE email email VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE password firstname VARCHAR(155) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
        $this->addSql('CREATE INDEX IDX_81398E09708A0E0 ON customer (gender_id)');
    }
}
