<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161025100909 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE category ADD shop_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql(
            'ALTER TABLE category ADD CONSTRAINT FK_64C19C14D16C4DD FOREIGN KEY (shop_id) REFERENCES shop (id)'
        );
        $this->addSql('CREATE INDEX IDX_64C19C14D16C4DD ON category (shop_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() != 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C14D16C4DD');
        $this->addSql('DROP INDEX IDX_64C19C14D16C4DD ON category');
        $this->addSql('ALTER TABLE category DROP shop_id');
    }
}
