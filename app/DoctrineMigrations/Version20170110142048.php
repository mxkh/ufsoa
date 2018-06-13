<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170110142048 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE selection (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', is_active TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_96A50CD74D16C4DD (shop_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE selection_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name LONGTEXT NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(155) NOT NULL, UNIQUE INDEX UNIQ_3E144314989D9B62 (slug), INDEX IDX_3E1443142C2AC5D3 (translatable_id), UNIQUE INDEX selection_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE selection ADD CONSTRAINT FK_96A50CD74D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)');
        $this->addSql('ALTER TABLE selection_translation ADD CONSTRAINT FK_3E1443142C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES selection (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product CHANGE is_active is_active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE selection_translation DROP FOREIGN KEY FK_3E1443142C2AC5D3');
        $this->addSql('DROP TABLE selection');
        $this->addSql('DROP TABLE selection_translation');
        $this->addSql('ALTER TABLE product CHANGE is_active is_active TINYINT(1) DEFAULT \'1\' NOT NULL');
    }
}
