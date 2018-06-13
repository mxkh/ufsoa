<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170306130430 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE branch (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', city_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, number INT NOT NULL, ref VARCHAR(255) NOT NULL, INDEX IDX_BB861B1F8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE street (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', city_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(155) NOT NULL, type VARCHAR(155) NOT NULL, ref VARCHAR(255) NOT NULL, INDEX IDX_F0EED3D88BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE branch ADD CONSTRAINT FK_BB861B1F8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE street ADD CONSTRAINT FK_F0EED3D88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE city ADD ref VARCHAR(255) NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE branch');
        $this->addSql('DROP TABLE street');
        $this->addSql('ALTER TABLE city DROP ref');
    }
}
