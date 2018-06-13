<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170111093701 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE selection_item (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', selection_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_CB95FBE3E48EFE78 (selection_id), INDEX IDX_CB95FBE34584665A (product_id), UNIQUE INDEX selection_item_idx (selection_id, product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE selection_item ADD CONSTRAINT FK_CB95FBE3E48EFE78 FOREIGN KEY (selection_id) REFERENCES selection (id)');
        $this->addSql('ALTER TABLE selection_item ADD CONSTRAINT FK_CB95FBE34584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE selection_item');
    }
}
