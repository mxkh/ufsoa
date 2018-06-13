<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161130094226 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customer_profile (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', gender_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', firstname VARCHAR(155) DEFAULT NULL, lastname VARCHAR(155) DEFAULT NULL, birthday DATE DEFAULT NULL, UNIQUE INDEX UNIQ_9D8A0EB19395C3F3 (customer_id), INDEX IDX_9D8A0EB1708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_profile ADD CONSTRAINT FK_9D8A0EB19395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE customer_profile ADD CONSTRAINT FK_9D8A0EB1708A0E0 FOREIGN KEY (gender_id) REFERENCES gender (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customer_profile');
    }
}
