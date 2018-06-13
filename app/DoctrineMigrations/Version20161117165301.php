<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161117165301 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mime_type (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', media_enum_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(155) NOT NULL, template VARCHAR(155) NOT NULL, INDEX IDX_2100AA2E76B5081C (media_enum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mime_type ADD CONSTRAINT FK_2100AA2E76B5081C FOREIGN KEY (media_enum_id) REFERENCES media_enum (id)');
        $this->addSql('ALTER TABLE media ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mime_type');
        $this->addSql('ALTER TABLE media DROP created_at, DROP updated_at');
    }
}
