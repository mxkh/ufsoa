<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161112053723 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE media (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', media_enum_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', filename VARCHAR(255) NOT NULL, mimeType VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10C76B5081C (media_enum_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media_enum (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C76B5081C FOREIGN KEY (media_enum_id) REFERENCES media_enum (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C76B5081C');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE media_enum');
    }
}
