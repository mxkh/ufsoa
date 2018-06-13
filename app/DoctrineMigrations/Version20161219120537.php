<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161219120537 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employee (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', employee_group_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(155) NOT NULL, email VARCHAR(155) NOT NULL, phone VARCHAR(155) NOT NULL, birthday DATE DEFAULT NULL, password VARCHAR(64) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_5D9F75A16E6B8880 (employee_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee_group_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(128) NOT NULL, INDEX IDX_97B6048F2C2AC5D3 (translatable_id), UNIQUE INDEX employee_group_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A16E6B8880 FOREIGN KEY (employee_group_id) REFERENCES employee_group (id)');
        $this->addSql('ALTER TABLE employee_group_translation ADD CONSTRAINT FK_97B6048F2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES employee_group (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A16E6B8880');
        $this->addSql('ALTER TABLE employee_group_translation DROP FOREIGN KEY FK_97B6048F2C2AC5D3');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE employee_group');
        $this->addSql('DROP TABLE employee_group_translation');
    }
}
