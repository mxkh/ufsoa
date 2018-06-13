<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170127165244 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE payment_type (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', code VARCHAR(155) NOT NULL, UNIQUE INDEX UNIQ_AD5DC05D77153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_type_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_B0A8E8402C2AC5D3 (translatable_id), UNIQUE INDEX payment_type_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_type_translation ADD CONSTRAINT FK_B0A8E8402C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES payment_type (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE payment_type_translation DROP FOREIGN KEY FK_B0A8E8402C2AC5D3');
        $this->addSql('DROP TABLE payment_type');
        $this->addSql('DROP TABLE payment_type_translation');
    }
}
