<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161018064842 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geolocation ADD stores_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE geolocation ADD CONSTRAINT FK_9DC0E5B48D710F7F FOREIGN KEY (stores_id) REFERENCES store (id)');
        $this->addSql('CREATE INDEX IDX_9DC0E5B48D710F7F ON geolocation (stores_id)');
        $this->addSql('ALTER TABLE social_profile_enum ADD store_social_profiles_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE social_profile_enum ADD CONSTRAINT FK_64D588FA211EDA75 FOREIGN KEY (store_social_profiles_id) REFERENCES store_social_profile (id)');
        $this->addSql('CREATE INDEX IDX_64D588FA211EDA75 ON social_profile_enum (store_social_profiles_id)');
        $this->addSql('ALTER TABLE store DROP FOREIGN KEY FK_FF5758771C7B5678');
        $this->addSql('DROP INDEX IDX_FF5758771C7B5678 ON store');
        $this->addSql('ALTER TABLE store DROP geolocation_id');
        $this->addSql('ALTER TABLE store_social_profile DROP FOREIGN KEY FK_19BA624CDBF1074A');
        $this->addSql('DROP INDEX IDX_19BA624CDBF1074A ON store_social_profile');
        $this->addSql('ALTER TABLE store_social_profile DROP social_profile_enum_id');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE geolocation DROP FOREIGN KEY FK_9DC0E5B48D710F7F');
        $this->addSql('DROP INDEX IDX_9DC0E5B48D710F7F ON geolocation');
        $this->addSql('ALTER TABLE geolocation DROP stores_id');
        $this->addSql('ALTER TABLE social_profile_enum DROP FOREIGN KEY FK_64D588FA211EDA75');
        $this->addSql('DROP INDEX IDX_64D588FA211EDA75 ON social_profile_enum');
        $this->addSql('ALTER TABLE social_profile_enum DROP store_social_profiles_id');
        $this->addSql('ALTER TABLE store ADD geolocation_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE store ADD CONSTRAINT FK_FF5758771C7B5678 FOREIGN KEY (geolocation_id) REFERENCES geolocation (id)');
        $this->addSql('CREATE INDEX IDX_FF5758771C7B5678 ON store (geolocation_id)');
        $this->addSql('ALTER TABLE store_social_profile ADD social_profile_enum_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE store_social_profile ADD CONSTRAINT FK_19BA624CDBF1074A FOREIGN KEY (social_profile_enum_id) REFERENCES social_profile_enum (id)');
        $this->addSql('CREATE INDEX IDX_19BA624CDBF1074A ON store_social_profile (social_profile_enum_id)');
    }
}
