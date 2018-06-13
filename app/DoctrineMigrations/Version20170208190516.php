<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170208190516 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE social_network (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(64) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer_social_identity (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', social_network_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', name LONGTEXT NOT NULL, INDEX IDX_9F8C3B72FA413953 (social_network_id), INDEX IDX_9F8C3B729395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_social_identity ADD CONSTRAINT FK_9F8C3B72FA413953 FOREIGN KEY (social_network_id) REFERENCES social_network (id)');
        $this->addSql('ALTER TABLE customer_social_identity ADD CONSTRAINT FK_9F8C3B729395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE customer_social_identity DROP FOREIGN KEY FK_9F8C3B72FA413953');
        $this->addSql('DROP TABLE social_network');
        $this->addSql('DROP TABLE customer_social_identity');
    }
}
