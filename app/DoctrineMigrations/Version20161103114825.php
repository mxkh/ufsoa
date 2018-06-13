<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161103114825 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE feature (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', position INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_6C7AD9582C2AC5D3 (translatable_id), UNIQUE INDEX feature_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_value (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', feature_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_D429523D60E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature_value_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, value VARCHAR(128) NOT NULL, INDEX IDX_BA239CB12C2AC5D3 (translatable_id), UNIQUE INDEX feature_value_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feature_translation ADD CONSTRAINT FK_6C7AD9582C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES feature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feature_value ADD CONSTRAINT FK_D429523D60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE feature_value_translation ADD CONSTRAINT FK_BA239CB12C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES feature_value (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feature_translation DROP FOREIGN KEY FK_6C7AD9582C2AC5D3');
        $this->addSql('ALTER TABLE feature_value DROP FOREIGN KEY FK_D429523D60E4B879');
        $this->addSql('ALTER TABLE feature_value_translation DROP FOREIGN KEY FK_BA239CB12C2AC5D3');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE feature_translation');
        $this->addSql('DROP TABLE feature_value');
        $this->addSql('DROP TABLE feature_value_translation');
    }
}
