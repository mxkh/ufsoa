<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170216101223 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_variant_media (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_media_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_variant_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', position INT NOT NULL, UNIQUE INDEX UNIQ_304B22A3212FB7E2 (product_media_id), INDEX IDX_304B22A3A80EF684 (product_variant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_variant_media ADD CONSTRAINT FK_304B22A3212FB7E2 FOREIGN KEY (product_media_id) REFERENCES product_media (id)');
        $this->addSql('ALTER TABLE product_variant_media ADD CONSTRAINT FK_304B22A3A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_variant_media');
    }
}
