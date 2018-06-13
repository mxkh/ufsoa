<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161019122911 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contact_contact_enum');
        $this->addSql('ALTER TABLE contact ADD contact_enum_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6388D4DAFD4 FOREIGN KEY (contact_enum_id) REFERENCES contact_enum (id)');
        $this->addSql('CREATE INDEX IDX_4C62E6388D4DAFD4 ON contact (contact_enum_id)');
        $this->addSql('ALTER TABLE contact_enum ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE geolocation ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE shop_group ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE social_profile_enum ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE store_enum ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE store_social_profile ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contact_contact_enum (contact_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', contact_enum_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', INDEX IDX_AF61E9AE7A1254A (contact_id), INDEX IDX_AF61E9A8D4DAFD4 (contact_enum_id), PRIMARY KEY(contact_id, contact_enum_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_contact_enum ADD CONSTRAINT FK_AF61E9A8D4DAFD4 FOREIGN KEY (contact_enum_id) REFERENCES contact_enum (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_contact_enum ADD CONSTRAINT FK_AF61E9AE7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6388D4DAFD4');
        $this->addSql('DROP INDEX IDX_4C62E6388D4DAFD4 ON contact');
        $this->addSql('ALTER TABLE contact DROP contact_enum_id, DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE contact_enum DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE geolocation DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE shop_group DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE social_profile_enum DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE store_enum DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE store_social_profile DROP created_at, DROP updated_at');
    }
}
