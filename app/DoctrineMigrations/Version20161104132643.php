<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20161104132643 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product_feature (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', feature_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_CE0E6ED64584665A (product_id), INDEX IDX_CE0E6ED660E4B879 (feature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_feature_feature_value (product_feature_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', feature_value_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_C9387F5CF383E752 (product_feature_id), INDEX IDX_C9387F5C80CD149D (feature_value_id), PRIMARY KEY(product_feature_id, feature_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_feature ADD CONSTRAINT FK_CE0E6ED64584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_feature ADD CONSTRAINT FK_CE0E6ED660E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id)');
        $this->addSql('ALTER TABLE product_feature_feature_value ADD CONSTRAINT FK_C9387F5CF383E752 FOREIGN KEY (product_feature_id) REFERENCES product_feature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_feature_feature_value ADD CONSTRAINT FK_C9387F5C80CD149D FOREIGN KEY (feature_value_id) REFERENCES feature_value (id) ON DELETE CASCADE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product_feature_feature_value DROP FOREIGN KEY FK_C9387F5CF383E752');
        $this->addSql('DROP TABLE product_feature');
        $this->addSql('DROP TABLE product_feature_feature_value');
    }
}
