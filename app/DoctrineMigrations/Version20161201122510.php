<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161201122510 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', locale VARCHAR(155) NOT NULL, name VARCHAR(155) DEFAULT NULL, description LONGTEXT DEFAULT NULL, short_description LONGTEXT DEFAULT NULL, slug VARCHAR(155) NOT NULL, UNIQUE INDEX UNIQ_1846DB70989D9B62 (slug), INDEX IDX_1846DB702C2AC5D3 (translatable_id), UNIQUE INDEX product_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_translation ADD CONSTRAINT FK_1846DB702C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product ADD sale_price DOUBLE PRECISION DEFAULT NULL, ADD price DOUBLE PRECISION DEFAULT NULL, DROP slug, DROP description, DROP short_description, DROP min_price, DROP max_price');
        $this->addSql('ALTER TABLE product_variant ADD store_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE product_variant ADD CONSTRAINT FK_209AA41DB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('CREATE INDEX IDX_209AA41DB092A811 ON product_variant (store_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product_translation');
        $this->addSql('ALTER TABLE product ADD slug VARCHAR(155) NOT NULL COLLATE utf8mb4_unicode_ci, ADD description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD short_description LONGTEXT DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD min_price INT DEFAULT NULL, ADD max_price INT DEFAULT NULL, DROP sale_price, DROP price');
        $this->addSql('ALTER TABLE product_variant DROP FOREIGN KEY FK_209AA41DB092A811');
        $this->addSql('DROP INDEX IDX_209AA41DB092A811 ON product_variant');
        $this->addSql('ALTER TABLE product_variant DROP store_id');
    }
}
