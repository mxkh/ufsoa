<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170418105721 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE favorite');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE favorite (id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', customer_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', product_variant_id CHAR(36) DEFAULT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:uuid)\', UNIQUE INDEX favorite_idx (customer_id, product_id, product_variant_id), INDEX IDX_68C58ED99395C3F3 (customer_id), INDEX IDX_68C58ED94584665A (product_id), INDEX IDX_68C58ED9A80EF684 (product_variant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED94584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED99395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED9A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id)');
    }
}
